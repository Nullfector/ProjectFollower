--
-- PostgreSQL database dump
--

\restrict xIV65CE3gfbHyuSWjNnzaMgNLZ4Ire1HPKCL9qN4SgmuGhM1CSI39UdwW0x6PnO

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: czas_końca_projektu_dodanie_zadania(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."czas_końca_projektu_dodanie_zadania"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    czas_końca INT;
    czas_końca_fakt DATE;
BEGIN
    SELECT p.id_zadania_końcowego INTO
    czas_końca FROM Projekt p WHERE
    id_P = NEW.nadrzędny_projekt;

    SELECT z.czas_zakończenia INTO
    czas_końca_fakt FROM Zadanie z WHERE
    id_Za = czas_końca;

    IF czas_końca IS NULL OR czas_końca_fakt<NEW.czas_zakończenia THEN
        UPDATE Projekt SET id_zadania_końcowego = NEW.id_Za WHERE id_P = NEW.nadrzędny_projekt;
    end if;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public."czas_końca_projektu_dodanie_zadania"() OWNER TO postgres;

--
-- Name: czas_końca_projektu_usunięcie_zadania(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."czas_końca_projektu_usunięcie_zadania"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    id_zadania_usuwanego INT;
    id_projektu INT;
BEGIN
    SELECT p.id_zadania_końcowego, p.id_P INTO
    id_zadania_usuwanego, id_projektu FROM Projekt p WHERE
    id_P = OLD.nadrzędny_projekt;

    WITH time_stamp AS (SELECT z.id_Za, z.czas_zakończenia
    FROM Zadanie z WHERE z.nadrzędny_projekt = id_projektu
    ORDER BY z.czas_zakończenia DESC
    LIMIT 1) UPDATE Projekt SET id_zadania_końcowego = ts.id_Za FROM time_stamp ts WHERE id_P = id_projektu;

    RETURN OLD;
END;
$$;


ALTER FUNCTION public."czas_końca_projektu_usunięcie_zadania"() OWNER TO postgres;

--
-- Name: edycja_projektu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.edycja_projektu() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    --Admin projektu może istnieć, ale może też go nie być - może być w asocjacji, lub nie - nie ma to znaczenia
    BEGIN
        IF OLD.archiwalne = true OR OLD.zakończony = true
        THEN
            --RAISE NOTICE 'Projekt % jest archiwalny lub zakończony - nie można go edytować!', OLD.nazwa_projektu;
            --RETURN NULL;
            RAISE EXCEPTION 'Projekt jest archiwalny lub zakończony - nie można go edytować!' USING ERRCODE='P1012';
        end if;

        IF NOT OLD.czas_startu > current_date OR NOT NEW.czas_startu > current_date THEN
            --RAISE NOTICE 'Czas startowy projektu % nie może zostać zedytowany!', OLD.nazwa_projektu;
            --RETURN NULL;
            RAISE EXCEPTION 'Czas startowy projektu nie może zostać zedytowany!' USING ERRCODE='P1013';
        end if;

        IF NOT EXISTS(SELECT 1 FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p AND zakończony = false)
            AND NEW.zakończony = true THEN
            --RAISE NOTICE 'Projket % nie może zostać zakończony gdyż nie zostały wykonane wszystkie zadania!', OLD.nazwa_projektu;
            --RETURN NULL;
            RAISE EXCEPTION 'Projket nie może zostać zakończony gdyż nie zostały wykonane wszystkie zadania!' USING ERRCODE='P1014';
        end if;

        IF NEW.archiwalne = true THEN
            --DELETE FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p;
            RAISE NOTICE 'Projket % został zarchiwizowany!', OLD.nazwa_projektu;
            RETURN NEW;
        end if;

        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.edycja_projektu() OWNER TO postgres;

--
-- Name: edycja_zadania(integer, character varying, integer, date, date, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.edycja_zadania(id_edytowanego integer, nowa_nazwa character varying, nowy_priorytet integer, nowy_czas_start date, "nowy_czas_zakończenia" date, nowy_stan boolean) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF (SELECT zakończony FROM Zadanie WHERE id_Za = id_edytowanego) = true THEN
            RAISE NOTICE 'Zadanie % zostało zakończone - edycja nie możliwa!',nowa_nazwa;
            RETURN;
        end if;

        IF nowy_czas_start >= nowy_czas_zakończenia THEN
            RAISE NOTICE 'Na paradoksy czasowe nie ma tu miejsca!';
            RETURN;
        end if;

        IF (SELECT czas_staru FROM Zadanie WHERE id_Za = id_edytowanego) > nowy_czas_start
            AND  EXISTS(SELECT 1 FROM Asocjacja_Za_Self JOIN Zadanie ON id_Za = id_zad_podległe WHERE id_zad_blokujące = id_edytowanego
                        AND czas_zakończenia >= nowy_czas_start) THEN
            RAISE NOTICE 'Nie można zedytować czasu rozpoczęcia - zachodzi na inne powiązane zadania!';
        ELSE
            UPDATE Zadanie SET czas_staru = nowy_czas_start WHERE id_Za = id_edytowanego;
        end if;

        IF (SELECT czas_zakończenia FROM Zadanie WHERE id_Za = id_edytowanego) < nowy_czas_zakończenia THEN
            PERFORM przesuwanie_czasu_wprzód(id_edytowanego,nowy_czas_zakończenia);
        end if;

        UPDATE Zadanie SET nazwa_zadania = nowa_nazwa, priorytet = nowy_priorytet, czas_zakończenia = nowy_czas_zakończenia,
        zakończony = nowy_stan WHERE id_Za = id_edytowanego;
        --mam nadzieje że logicznych jebań nie ma!
    end;
    $$;


ALTER FUNCTION public.edycja_zadania(id_edytowanego integer, nowa_nazwa character varying, nowy_priorytet integer, nowy_czas_start date, "nowy_czas_zakończenia" date, nowy_stan boolean) OWNER TO postgres;

--
-- Name: edycja_zespołu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."edycja_zespołu"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF OLD.archiwalne = true
        THEN
            --RAISE NOTICE 'Zespoł % jest archiwalny - nie można go edytować!', OLD.nazwa;
            --RETURN NULL;
            RAISE EXCEPTION 'Zespoł jest archiwalny - nie można go edytować!' USING ERRCODE='P1010';
        end if;

        IF NEW.lider IS NOT NULL THEN
            IF NOT EXISTS(SELECT 1 FROM Asocjacja_U_Ze WHERE id_u = NEW.lider AND id_ze = NEW.id_ze) THEN
                --UPDATE Asocjacja_U_Ze SET id_U = NEW.lider WHERE id_U = OLD.lider AND id_ze = OLD.id_ze;
                INSERT INTO Asocjacja_U_Ze (id_U,id_Ze) VALUES (NEW.lider,NEW.id_Ze);
            ELSE
                RAISE NOTICE 'Lider zespołu został zamieniony!';
              --  RAISE NOTICE 'Lider zespołu został usunięty!';
                --DELETE FROM Asocjacja_U_Ze WHERE id_U = OLD.lider AND id_ze = OLD.id_ze;
            end if;
        end if;

        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public."edycja_zespołu"() OWNER TO postgres;

--
-- Name: przesuwanie_czasu_wprzód(integer, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."przesuwanie_czasu_wprzód"(id_pierwszego integer, "nowy_czas_końca" date) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN
        WITH RECURSIVE przejscie AS(
            SELECT aso.id_zad_blokujące AS id, ARRAY[aso.id_zad_blokujące] AS path, nowy_czas_końca - z.czas_staru +1 AS move
            FROM asocjacja_za_self aso
            JOIN Zadanie z ON z.id_Za = aso.id_zad_blokujące
            WHERE aso.id_zad_podległe = id_pierwszego AND
            z.czas_staru <= nowy_czas_końca --wliczamy sytuacje że 1 dnia coś się jednocześnie kończy i zaczyna - nie wolno

            UNION ALL

            SELECT aso.id_zad_blokujące,
            p.path || aso.id_zad_blokujące, (SELECT czas_zakończenia FROM Zadanie WHERE id_Za = aso.id_zad_podległe)+
                                 p.move - zb.czas_staru +1
            FROM przejscie p
            JOIN Asocjacja_Za_Self aso ON aso.id_zad_podległe = p.id
            JOIN Zadanie zb ON zb.id_Za = aso.id_zad_blokujące
            AND zb.czas_staru <= (SELECT czas_zakończenia FROM Zadanie WHERE id_Za = aso.id_zad_podległe)+
                                 p.move
        ),
        affected AS (
            SELECT DISTINCT id,MAX(move) as move FROM przejscie
            GROUP BY id
        )
        UPDATE Zadanie z
          SET czas_zakończenia = (czas_zakończenia + a.move),
              czas_staru = (czas_staru + a.move)
          FROM affected a WHERE z.id_Za = a.id;
          --WHERE z.id_Za IN (SELECT id FROM affected);
    end;
    $$;


ALTER FUNCTION public."przesuwanie_czasu_wprzód"(id_pierwszego integer, "nowy_czas_końca" date) OWNER TO postgres;

--
-- Name: tworzenie_edycja_aso_u_ze(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_edycja_aso_u_ze() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(Select 1 From Asocjacja_u_ze WHERE id_u = NEW.id_u AND id_ze = NEW.id_ze) THEN
            --RAISE NOTICE 'Operacja się nie powiodła - wnętrze się powtarza!';
            --RETURN NULL;
            RAISE EXCEPTION 'Operacja się nie powiodła - wnętrze się powtarza!' USING ERRCODE='P1011';
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_edycja_aso_u_ze() OWNER TO postgres;

--
-- Name: tworzenie_edycja_aso_za_self(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_edycja_aso_za_self() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF (SELECT czas_zakończenia FROM Zadanie WHERE id_Za = NEW.id_zad_podległe) >= (SELECT czas_staru FROM Zadanie WHERE id_Za = NEW.id_zad_blokujące)
            THEN
            RAISE NOTICE 'Nie można dodać nowego rekordu - paradoks czasowy!';
            RETURN NULL;
        end if;

        IF EXISTS(Select 1 From Asocjacja_za_self WHERE id_zad_podległe = NEW.id_zad_podległe AND id_zad_blokujące = NEW.id_zad_blokujące) THEN
            RAISE NOTICE 'Nie można dodać nowego rekordu - wnętrze się powtarza!';
            RETURN NULL;
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_edycja_aso_za_self() OWNER TO postgres;

--
-- Name: tworzenie_edycja_aso_za_ze(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_edycja_aso_za_ze() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(Select 1 From Asocjacja_za_ze WHERE id_zespolu = NEW.id_zespolu AND id_zadania = NEW.id_zadania) THEN
            RAISE NOTICE 'Operacja się nie powiodła - wnętrze się powtarza!';
            RETURN NULL;
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_edycja_aso_za_ze() OWNER TO postgres;

--
-- Name: tworzenie_edycja_usera(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_edycja_usera() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(Select 1 From Użytkownik WHERE login = NEW.login AND id_u != NEW.id_u) OR
           EXISTS(Select 1 From Użytkownik WHERE adres_mail = NEW.adres_mail AND id_u != NEW.id_u) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - wnętrze się powtarza!' USING ERRCODE='P1001';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - wnętrze się powtarza!';
            --RETURN NULL;
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_edycja_usera() OWNER TO postgres;

--
-- Name: tworzenie_edycje_typy(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_edycje_typy() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(SELECT 1 FROM Typ_projektu WHERE nazwa_typu = NEW.nazwa_typu) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - taki typ już istnieje!' USING ERRCODE='P1005';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - taki typ już istnieje!';
            --RETURN NULL;
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_edycje_typy() OWNER TO postgres;

--
-- Name: tworzenie_projektu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_projektu() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(SELECT 1 FROM Projekt WHERE nazwa_projektu = NEW.nazwa_projektu) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - projekt o takiej nazwie już istnieje!' USING ERRCODE = 'P1002';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - projekt o takiej nazwie już istnieje!';
            --RETURN NULL;
        end if;
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_projektu() OWNER TO postgres;

--
-- Name: tworzenie_zadania(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tworzenie_zadania() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(SELECT 1 FROM Zadanie WHERE nadrzędny_projekt = NEW.nadrzędny_projekt AND
                        nazwa_zadania = NEW.nazwa_zadania) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - zadanie o takiej nazwie już istnieje w projekcie!' USING ERRCODE='P1003';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - zadanie o takiej nazwie już istnieje w projekcie!';
            --RETURN NULL;
        end if;

        IF EXISTS(SELECT 1 FROM Projekt WHERE id_p = NEW.nadrzędny_projekt AND (zakończony = true OR archiwalne = true))
            THEN
            RAISE EXCEPTION 'Nie dodasz zdania do projektu który się zakończył lub został zarchiwizowany!' USING ERRCODE='P1004';
            --RAISE NOTICE 'Nie dodasz zdania do projektu który się zakończył lub został zarchiwizowany!';
            --RETURN NULL;
        end if;

        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public.tworzenie_zadania() OWNER TO postgres;

--
-- Name: tworzenie_zespołu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."tworzenie_zespołu"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(SELECT 1 FROM Zespół WHERE nazwa = NEW.nazwa) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - wnętrze się powtarza!' USING ERRCODE='P1006';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - wnętrze się powtarza!';
            --RETURN NULL;
        end if;
        IF NOT EXISTS(SELECT 1 FROM Użytkownik WHERE id_u = NEW.lider) THEN
            RAISE EXCEPTION 'Nie można dodać nowego rekordu - taki użytkownik nie istnieje!' USING ERRCODE='P1007';
            --RAISE NOTICE 'Nie można dodać nowego rekordu - taki użytkownik nie istnieje!';
            --RETURN NULL;
        end if;
        --INSERT INTO Asocjacja_U_Ze (id_u,id_ze) VALUES (NEW.lider,NEW.id_ze);
        RETURN NEW;
    end;
    $$;


ALTER FUNCTION public."tworzenie_zespołu"() OWNER TO postgres;

--
-- Name: usunięcie_zadania_powiązanego_asocjacja(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."usunięcie_zadania_powiązanego_asocjacja"(id_zadania_usuwanego integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN

    INSERT INTO Asocjacja_Za_Self(id_zad_podległe,id_zad_blokujące) SELECT azs.id_zad_podległe, azs1.id_zad_blokujące
    FROM Asocjacja_Za_Self azs JOIN Asocjacja_Za_Self azs1 ON azs.id_zad_blokujące = id_zadania_usuwanego
    AND azs.id_zad_blokujące = azs1.id_zad_podległe;

    DELETE FROM Asocjacja_Za_Self WHERE id_zad_podległe = id_zadania_usuwanego OR id_zad_blokujące = id_zadania_usuwanego;
END;
$$;


ALTER FUNCTION public."usunięcie_zadania_powiązanego_asocjacja"(id_zadania_usuwanego integer) OWNER TO postgres;

--
-- Name: usuwanie_projektu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.usuwanie_projektu() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        --DELETE FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p; --to tutaj usuwa wszstkie zadanie projektowe (lub je archuje)
        IF (OLD.czas_startu < current_date) OR NOT EXISTS(
            SELECT 1 FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p AND zakończony = true
        )THEN
            --DELETE FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p;
            RETURN OLD;
        end if;

        RAISE NOTICE 'Projekt % został przynajmniej częściowo wykonany. Dodaje flagę ARCH', OLD.nazwa_projektu;
        --OLD.archiwalne = true;
        UPDATE Projekt SET archiwalne = true WHERE id_p = OLD.id_p;
        RETURN NULL;
    end;
    $$;


ALTER FUNCTION public.usuwanie_projektu() OWNER TO postgres;

--
-- Name: usuwanie_projektu_funkcja(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.usuwanie_projektu_funkcja(id_usuwanego integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
    BEGIN
        --DELETE FROM Zadanie WHERE nadrzędny_projekt = OLD.id_p; --to tutaj usuwa wszstkie zadanie projektowe (lub je archuje)
        IF ((SELECT czas_startu FROM Projekt WHERE id_p=id_usuwanego) < current_date) OR NOT EXISTS(
            SELECT 1 FROM Zadanie WHERE nadrzędny_projekt = id_usuwanego AND zakończony = true
        )THEN
            DELETE FROM Zadanie WHERE nadrzędny_projekt = id_usuwanego;
            --RETURN OLD;
            DELETE FROM Projekt WHERE id_p = id_usuwanego;
            RETURN 'ok';
        end if;

        RAISE NOTICE 'Projekt został przynajmniej częściowo wykonany. Dodaje flagę ARCH';
        --OLD.archiwalne = true;
        UPDATE Projekt SET archiwalne = true WHERE id_p = id_usuwanego;
        RETURN 'almost_ok';
    end;
    $$;


ALTER FUNCTION public.usuwanie_projektu_funkcja(id_usuwanego integer) OWNER TO postgres;

--
-- Name: usuwanie_usera(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.usuwanie_usera() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(SELECT 1 FROM Zespół WHERE lider = OLD.id_u) THEN
            RAISE EXCEPTION 'Usuwany użytkownik jest liderem przynajmniej 1 zespołu - nie można go usunąć!' USING ERRCODE='P1008';
            --RAISE NOTICE 'Usuwany użytkownik jest liderem przynajmniej 1 zespołu - nie można go usunąć!';
            --RETURN NULL;
        end if;

        IF EXISTS(SELECT 1 FROM Projekt WHERE admin = OLD.id_u AND (zakończony = true OR archiwalne = true)) THEN
            RAISE EXCEPTION 'Usuwany użytkownik jest adminem przynajmniej 1 zakończonego lub archiwalnego projektu - nie można go usunąć!' USING ERRCODE='P1009';
            --RAISE NOTICE 'Usuwany użytkownik jest adminem przynajmniej 1 zakończonego lub archiwalnego projektu - nie można go usunąć!';
            --RETURN NULL;
        end if;

    IF EXISTS(SELECT 1 FROM Projekt WHERE admin = OLD.id_u) THEN
        UPDATE Projekt SET admin = null WHERE admin=OLD.id_u AND NOT (zakończony = true OR archiwalne = true);
        end if;

        DELETE FROM Asocjacja_U_Ze WHERE id_U = OLD.id_u;
        UPDATE Projekt SET admin = null WHERE admin = OLD.id_u;
        RETURN OLD;
    end;
    $$;


ALTER FUNCTION public.usuwanie_usera() OWNER TO postgres;

--
-- Name: usuwanie_zadania(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.usuwanie_zadania() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF (
            OLD.zakończony = true
        )
        THEN
            --RAISE NOTICE 'Zadanie % już zostało zrealizowane - nie mozna usunąć.', OLD.nazwa_zadania;
            --RETURN NULL;
            RAISE EXCEPTION 'Zadanie już zostało zrealizowane - nie mozna usunąć.' USING ERRCODE='P1015';
        end if;
        PERFORM usunięcie_zadania_powiązanego_asocjacja(OLD.id_Za);

        IF EXISTS (
            SELECT 1 FROM Asocjacja_Za_Ze WHERE id_zadania = OLD.id_Za
        ) THEN
            DELETE FROM Asocjacja_Za_Ze WHERE id_zadania = OLD.id_Za;
            RAISE NOTICE 'Usunięto asocjacje zadania z zespołami.';
        end if;

        RETURN OLD;
    end;
    $$;


ALTER FUNCTION public.usuwanie_zadania() OWNER TO postgres;

--
-- Name: usuwanie_zespołu(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."usuwanie_zespołu"() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(
            SELECT 1 FROM asocjacja_za_ze
            WHERE id_zespolu = OLD.id_Ze
        ) AND (
            SELECT zakończony FROM Zadanie WHERE id_Za = OLD.id_Ze) = true
        THEN
            --OLD.archiwalne = true;
            RAISE NOTICE 'Zespoł % jest przypisany do zakończonego zadania. Dodano etykiete RETIRED', OLD.nazwa;
            UPDATE Zespół SET archiwalne = true WHERE id_ze = OLD.id_ze;
            RETURN NULL;
        end if;

        DELETE FROm Asocjacja_Za_Ze WHERE id_zespolu=OLD.id_ze;
        DELETE FROM Asocjacja_U_Ze WHERE id_ze = OLD.id_ze;
        RETURN OLD;
    end;
    $$;


ALTER FUNCTION public."usuwanie_zespołu"() OWNER TO postgres;

--
-- Name: usuwanie_zespołu_funkcja(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."usuwanie_zespołu_funkcja"(id_usuwanego integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF EXISTS(
            SELECT 1 FROM asocjacja_za_ze aso JOIN Zadanie z ON z.id_Za=aso.id_zadania
            WHERE aso.id_zespolu = id_usuwanego AND z.zakończony=true
        )
        THEN
            RAISE NOTICE 'Zespoł jest przypisany do zakończonego zadania. Dodano etykiete RETIRED';
            UPDATE Zespół SET archiwalne = true WHERE id_ze = id_usuwanego;
            RETURN 'almost_ok';
        end if;

        DELETE FROm Asocjacja_Za_Ze WHERE id_zespolu=id_usuwanego;
        DELETE FROM Asocjacja_U_Ze WHERE id_ze = id_usuwanego;
        RETURN 'ok';
    end;
    $$;


ALTER FUNCTION public."usuwanie_zespołu_funkcja"(id_usuwanego integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: asocjacja_u_ze; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asocjacja_u_ze (
    id_asoc_u_ze integer NOT NULL,
    id_u integer,
    id_ze integer
);


ALTER TABLE public.asocjacja_u_ze OWNER TO postgres;

--
-- Name: asocjacja_u_ze_id_asoc_u_ze_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asocjacja_u_ze_id_asoc_u_ze_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asocjacja_u_ze_id_asoc_u_ze_seq OWNER TO postgres;

--
-- Name: asocjacja_u_ze_id_asoc_u_ze_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asocjacja_u_ze_id_asoc_u_ze_seq OWNED BY public.asocjacja_u_ze.id_asoc_u_ze;


--
-- Name: asocjacja_za_self; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asocjacja_za_self (
    id_aso_za integer NOT NULL,
    "id_zad_podległe" integer,
    "id_zad_blokujące" integer,
    CONSTRAINT con_aso_z3 CHECK (("id_zad_podległe" <> "id_zad_blokujące"))
);


ALTER TABLE public.asocjacja_za_self OWNER TO postgres;

--
-- Name: asocjacja_za_self_id_aso_za_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asocjacja_za_self_id_aso_za_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asocjacja_za_self_id_aso_za_seq OWNER TO postgres;

--
-- Name: asocjacja_za_self_id_aso_za_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asocjacja_za_self_id_aso_za_seq OWNED BY public.asocjacja_za_self.id_aso_za;


--
-- Name: asocjacja_za_ze; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.asocjacja_za_ze (
    id_aso_za_ze integer NOT NULL,
    id_zespolu integer,
    id_zadania integer
);


ALTER TABLE public.asocjacja_za_ze OWNER TO postgres;

--
-- Name: asocjacja_za_ze_id_aso_za_ze_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.asocjacja_za_ze_id_aso_za_ze_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asocjacja_za_ze_id_aso_za_ze_seq OWNER TO postgres;

--
-- Name: asocjacja_za_ze_id_aso_za_ze_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.asocjacja_za_ze_id_aso_za_ze_seq OWNED BY public.asocjacja_za_ze.id_aso_za_ze;


--
-- Name: projekt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.projekt (
    id_p integer NOT NULL,
    nazwa_projektu character varying(40) NOT NULL,
    typ_projektu integer,
    admin integer,
    czas_startu date DEFAULT CURRENT_DATE NOT NULL,
    "id_zadania_końcowego" integer,
    "zakończony" boolean DEFAULT false,
    archiwalne boolean DEFAULT false
);


ALTER TABLE public.projekt OWNER TO postgres;

--
-- Name: projekt_id_p_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.projekt_id_p_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.projekt_id_p_seq OWNER TO postgres;

--
-- Name: projekt_id_p_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.projekt_id_p_seq OWNED BY public.projekt.id_p;


--
-- Name: role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role (
    id_r integer NOT NULL,
    opis character varying(40) NOT NULL
);


ALTER TABLE public.role OWNER TO postgres;

--
-- Name: role_id_r_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_id_r_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_id_r_seq OWNER TO postgres;

--
-- Name: role_id_r_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_id_r_seq OWNED BY public.role.id_r;


--
-- Name: typ_projektu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.typ_projektu (
    id_t integer NOT NULL,
    nazwa_typu character varying(50) NOT NULL
);


ALTER TABLE public.typ_projektu OWNER TO postgres;

--
-- Name: typ_projektu_id_t_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.typ_projektu_id_t_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.typ_projektu_id_t_seq OWNER TO postgres;

--
-- Name: typ_projektu_id_t_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.typ_projektu_id_t_seq OWNED BY public.typ_projektu.id_t;


--
-- Name: użytkownik; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."użytkownik" (
    id_u integer NOT NULL,
    imie character varying(50) NOT NULL,
    nazwisko character varying(50) NOT NULL,
    adres_mail character varying(255) NOT NULL,
    login character varying(50) NOT NULL,
    haslo character varying(50) NOT NULL,
    rola integer
);


ALTER TABLE public."użytkownik" OWNER TO postgres;

--
-- Name: użytkownik_id_u_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."użytkownik_id_u_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."użytkownik_id_u_seq" OWNER TO postgres;

--
-- Name: użytkownik_id_u_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."użytkownik_id_u_seq" OWNED BY public."użytkownik".id_u;


--
-- Name: zadanie; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.zadanie (
    id_za integer NOT NULL,
    nazwa_zadania character varying(40) NOT NULL,
    "nadrzędny_projekt" integer,
    priorytet integer,
    czas_staru date NOT NULL,
    "czas_zakończenia" date NOT NULL,
    "zakończony" boolean DEFAULT false,
    CONSTRAINT con_za1 CHECK (((priorytet > 0) AND (priorytet < 6))),
    CONSTRAINT con_za2 CHECK ((czas_staru < "czas_zakończenia"))
);


ALTER TABLE public.zadanie OWNER TO postgres;

--
-- Name: zadanie_id_za_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.zadanie_id_za_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.zadanie_id_za_seq OWNER TO postgres;

--
-- Name: zadanie_id_za_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.zadanie_id_za_seq OWNED BY public.zadanie.id_za;


--
-- Name: zespół; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."zespół" (
    id_ze integer NOT NULL,
    nazwa character varying(100) NOT NULL,
    lider integer,
    archiwalne boolean DEFAULT false
);


ALTER TABLE public."zespół" OWNER TO postgres;

--
-- Name: zespół_id_ze_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."zespół_id_ze_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."zespół_id_ze_seq" OWNER TO postgres;

--
-- Name: zespół_id_ze_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."zespół_id_ze_seq" OWNED BY public."zespół".id_ze;


--
-- Name: asocjacja_u_ze id_asoc_u_ze; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_u_ze ALTER COLUMN id_asoc_u_ze SET DEFAULT nextval('public.asocjacja_u_ze_id_asoc_u_ze_seq'::regclass);


--
-- Name: asocjacja_za_self id_aso_za; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_self ALTER COLUMN id_aso_za SET DEFAULT nextval('public.asocjacja_za_self_id_aso_za_seq'::regclass);


--
-- Name: asocjacja_za_ze id_aso_za_ze; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_ze ALTER COLUMN id_aso_za_ze SET DEFAULT nextval('public.asocjacja_za_ze_id_aso_za_ze_seq'::regclass);


--
-- Name: projekt id_p; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projekt ALTER COLUMN id_p SET DEFAULT nextval('public.projekt_id_p_seq'::regclass);


--
-- Name: role id_r; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role ALTER COLUMN id_r SET DEFAULT nextval('public.role_id_r_seq'::regclass);


--
-- Name: typ_projektu id_t; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.typ_projektu ALTER COLUMN id_t SET DEFAULT nextval('public.typ_projektu_id_t_seq'::regclass);


--
-- Name: użytkownik id_u; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."użytkownik" ALTER COLUMN id_u SET DEFAULT nextval('public."użytkownik_id_u_seq"'::regclass);


--
-- Name: zadanie id_za; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zadanie ALTER COLUMN id_za SET DEFAULT nextval('public.zadanie_id_za_seq'::regclass);


--
-- Name: zespół id_ze; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."zespół" ALTER COLUMN id_ze SET DEFAULT nextval('public."zespół_id_ze_seq"'::regclass);


--
-- Data for Name: asocjacja_u_ze; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asocjacja_u_ze (id_asoc_u_ze, id_u, id_ze) FROM stdin;
13	2	5
14	14	11
15	16	12
16	15	13
17	2	11
23	12	11
24	16	11
25	17	12
26	4	12
28	2	13
\.


--
-- Data for Name: asocjacja_za_self; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asocjacja_za_self (id_aso_za, "id_zad_podległe", "id_zad_blokujące") FROM stdin;
68	18	25
78	32	46
79	46	35
80	46	36
81	34	35
82	35	37
83	36	38
84	37	40
85	39	40
86	40	41
87	41	43
88	40	42
90	38	40
91	46	39
55	15	17
56	16	17
57	17	18
58	17	19
59	19	20
61	18	24
62	21	23
63	20	24
65	24	25
66	20	23
67	23	25
\.


--
-- Data for Name: asocjacja_za_ze; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.asocjacja_za_ze (id_aso_za_ze, id_zespolu, id_zadania) FROM stdin;
13	7	15
14	7	16
17	11	43
18	11	37
\.


--
-- Data for Name: projekt; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.projekt (id_p, nazwa_projektu, typ_projektu, admin, czas_startu, "id_zadania_końcowego", "zakończony", archiwalne) FROM stdin;
2	projektTestowy	1	\N	2026-01-31	\N	f	t
6	projekcik2	1	\N	2026-03-11	29	f	f
7	ProjNow	25	4	2026-04-01	43	f	f
12	kolejny	1	\N	2026-04-12	\N	f	f
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role (id_r, opis) FROM stdin;
1	user
2	admin
\.


--
-- Data for Name: typ_projektu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.typ_projektu (id_t, nazwa_typu) FROM stdin;
1	Test poprawności
23	nowy_typ
25	profesjonalny test
\.


--
-- Data for Name: użytkownik; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."użytkownik" (id_u, imie, nazwisko, adres_mail, login, haslo, rola) FROM stdin;
2	Basia	Barbacka	adres2	bmaster	masło	2
3	Celina	Saska	adres3	saksonia8	masło	1
4	Dariusz	Damian	adres4	darosław	masło	1
7	Adam	Adamski	adres1	adam01	masło	1
8	Piotr	Nowak	mail@gmail.com	null	maslo	1
9	Adam	Kopernik	mail65@poczta.com	akom	maslo	1
12	Karol	Nawrot	mail@mail.com	kwan	maslo	1
13	Gabriela	Hiundaj	ghiu@mail.pl	ghami	maslo	1
15	Bartłomiej	Barski	barbar2004@firma.pl	bert56	maslo	1
16	Cyprian	Norwid	CNwid@firma.pl	Norcy	maslo	2
17	Dariusz	Damiański	dd@firma.pl	ddamian	maslo	1
14	Adrian	Adamuski	adamski@firma.pl	aaaa	maslo21	2
\.


--
-- Data for Name: zadanie; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.zadanie (id_za, nazwa_zadania, "nadrzędny_projekt", priorytet, czas_staru, "czas_zakończenia", "zakończony") FROM stdin;
15	1	2	\N	2026-02-07	2026-02-09	t
29	1	6	\N	2026-03-12	2026-03-14	f
30	12	6	\N	2026-03-11	2026-03-14	f
31	zadanie1	6	2	2026-03-12	2026-03-14	f
32	Zadanie 1	7	1	2026-04-01	2026-04-03	f
34	Zadanie 3	7	1	2026-04-05	2026-04-09	f
35	Zadanie 4	7	\N	2026-04-11	2026-04-13	f
36	Zadanie 5	7	\N	2026-04-12	2026-04-16	f
37	Zadanie 6	7	\N	2026-04-16	2026-04-22	f
38	Zadanie 7	7	\N	2026-04-17	2026-04-21	f
39	Zadanie 8	7	\N	2026-04-16	2026-04-18	f
40	Zadanie 9	7	\N	2026-04-23	2026-04-25	f
41	Zadanie 10	7	4	2026-04-26	2026-04-28	f
42	Zadanie 11	7	3	2026-04-26	2026-04-28	f
43	Zadanie 12	7	1	2026-04-29	2026-05-03	f
46	Zadanie 2	7	\N	2026-04-04	2026-04-07	f
16	2	2	\N	2026-02-07	2026-02-10	f
21	7	2	\N	2026-02-14	2026-02-20	f
25	12	2	\N	2026-03-06	2026-03-09	f
24	10	2	\N	2026-02-26	2026-02-28	f
20	6	2	\N	2026-02-23	2026-02-25	f
18	4	2	\N	2026-02-17	2026-02-21	f
23	9	2	\N	2026-02-26	2026-02-27	f
19	5	2	\N	2026-02-17	2026-02-22	f
17	3	2	\N	2026-02-11	2026-02-16	f
\.


--
-- Data for Name: zespół; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."zespół" (id_ze, nazwa, lider, archiwalne) FROM stdin;
5	grupka druga	2	f
7	testowa grupka	2	t
8	Kolejna grupka	4	f
12	Zespol beta	16	f
13	Zespol gamma	15	f
11	Zespol Alfa	14	f
\.


--
-- Name: asocjacja_u_ze_id_asoc_u_ze_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asocjacja_u_ze_id_asoc_u_ze_seq', 28, true);


--
-- Name: asocjacja_za_self_id_aso_za_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asocjacja_za_self_id_aso_za_seq', 91, true);


--
-- Name: asocjacja_za_ze_id_aso_za_ze_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.asocjacja_za_ze_id_aso_za_ze_seq', 18, true);


--
-- Name: projekt_id_p_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.projekt_id_p_seq', 12, true);


--
-- Name: role_id_r_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_id_r_seq', 2, true);


--
-- Name: typ_projektu_id_t_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.typ_projektu_id_t_seq', 25, true);


--
-- Name: użytkownik_id_u_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."użytkownik_id_u_seq"', 17, true);


--
-- Name: zadanie_id_za_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.zadanie_id_za_seq', 46, true);


--
-- Name: zespół_id_ze_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."zespół_id_ze_seq"', 13, true);


--
-- Name: asocjacja_u_ze asocjacja_u_ze_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_u_ze
    ADD CONSTRAINT asocjacja_u_ze_pkey PRIMARY KEY (id_asoc_u_ze);


--
-- Name: asocjacja_za_self asocjacja_za_self_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_self
    ADD CONSTRAINT asocjacja_za_self_pkey PRIMARY KEY (id_aso_za);


--
-- Name: asocjacja_za_ze asocjacja_za_ze_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_ze
    ADD CONSTRAINT asocjacja_za_ze_pkey PRIMARY KEY (id_aso_za_ze);


--
-- Name: projekt projekt_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projekt
    ADD CONSTRAINT projekt_pkey PRIMARY KEY (id_p);


--
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id_r);


--
-- Name: typ_projektu typ_projektu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.typ_projektu
    ADD CONSTRAINT typ_projektu_pkey PRIMARY KEY (id_t);


--
-- Name: użytkownik użytkownik_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."użytkownik"
    ADD CONSTRAINT "użytkownik_pkey" PRIMARY KEY (id_u);


--
-- Name: zadanie zadanie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zadanie
    ADD CONSTRAINT zadanie_pkey PRIMARY KEY (id_za);


--
-- Name: zespół zespół_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."zespół"
    ADD CONSTRAINT "zespół_pkey" PRIMARY KEY (id_ze);


--
-- Name: zadanie trg_czas_końca_dodanie; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER "trg_czas_końca_dodanie" AFTER INSERT ON public.zadanie FOR EACH ROW EXECUTE FUNCTION public."czas_końca_projektu_dodanie_zadania"();


--
-- Name: zadanie trg_czas_końca_edycja; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER "trg_czas_końca_edycja" AFTER UPDATE ON public.zadanie FOR EACH ROW EXECUTE FUNCTION public."czas_końca_projektu_dodanie_zadania"();


--
-- Name: zadanie trg_czas_końca_usunięcie; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER "trg_czas_końca_usunięcie" AFTER DELETE ON public.zadanie FOR EACH ROW EXECUTE FUNCTION public."czas_końca_projektu_usunięcie_zadania"();


--
-- Name: asocjacja_u_ze trg_dodanie_aso_uze; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_dodanie_aso_uze BEFORE INSERT ON public.asocjacja_u_ze FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_u_ze();


--
-- Name: asocjacja_za_self trg_dodanie_aso_zaself; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_dodanie_aso_zaself BEFORE INSERT ON public.asocjacja_za_self FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_za_self();


--
-- Name: asocjacja_za_ze trg_dodanie_aso_zaze; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_dodanie_aso_zaze BEFORE INSERT ON public.asocjacja_za_ze FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_za_ze();


--
-- Name: użytkownik trg_dodanie_user; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_dodanie_user BEFORE INSERT ON public."użytkownik" FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_usera();


--
-- Name: asocjacja_u_ze trg_edycja_aso_uze; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_aso_uze BEFORE UPDATE ON public.asocjacja_u_ze FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_u_ze();


--
-- Name: asocjacja_za_self trg_edycja_aso_zaself; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_aso_zaself BEFORE UPDATE ON public.asocjacja_za_self FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_za_self();


--
-- Name: asocjacja_za_ze trg_edycja_aso_zaze; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_aso_zaze BEFORE UPDATE ON public.asocjacja_za_ze FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_aso_za_ze();


--
-- Name: projekt trg_edycja_projektu; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_projektu BEFORE UPDATE ON public.projekt FOR EACH ROW EXECUTE FUNCTION public.edycja_projektu();


--
-- Name: typ_projektu trg_edycja_typ; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_typ BEFORE UPDATE ON public.typ_projektu FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycje_typy();


--
-- Name: użytkownik trg_edycja_user; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_edycja_user BEFORE UPDATE ON public."użytkownik" FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycja_usera();


--
-- Name: zespół trg_edycja_zespołu; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER "trg_edycja_zespołu" BEFORE UPDATE ON public."zespół" FOR EACH ROW EXECUTE FUNCTION public."edycja_zespołu"();


--
-- Name: projekt trg_tworzenie_projektu; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_tworzenie_projektu BEFORE INSERT ON public.projekt FOR EACH ROW EXECUTE FUNCTION public.tworzenie_projektu();


--
-- Name: typ_projektu trg_tworzenie_typ; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_tworzenie_typ BEFORE INSERT ON public.typ_projektu FOR EACH ROW EXECUTE FUNCTION public.tworzenie_edycje_typy();


--
-- Name: zadanie trg_tworzenie_zadania; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_tworzenie_zadania BEFORE INSERT ON public.zadanie FOR EACH ROW EXECUTE FUNCTION public.tworzenie_zadania();


--
-- Name: zespół trg_tworzenie_zespół; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER "trg_tworzenie_zespół" BEFORE INSERT ON public."zespół" FOR EACH ROW EXECUTE FUNCTION public."tworzenie_zespołu"();


--
-- Name: użytkownik trg_usuwanie_user; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_usuwanie_user BEFORE DELETE ON public."użytkownik" FOR EACH ROW EXECUTE FUNCTION public.usuwanie_usera();


--
-- Name: zadanie trg_usuwanie_zadania; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_usuwanie_zadania BEFORE DELETE ON public.zadanie FOR EACH ROW EXECUTE FUNCTION public.usuwanie_zadania();


--
-- Name: asocjacja_za_self con_aso_z1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_self
    ADD CONSTRAINT con_aso_z1 FOREIGN KEY ("id_zad_podległe") REFERENCES public.zadanie(id_za);


--
-- Name: asocjacja_za_self con_aso_z2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_self
    ADD CONSTRAINT con_aso_z2 FOREIGN KEY ("id_zad_blokujące") REFERENCES public.zadanie(id_za);


--
-- Name: asocjacja_za_ze con_aso_zz1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_ze
    ADD CONSTRAINT con_aso_zz1 FOREIGN KEY (id_zespolu) REFERENCES public."zespół"(id_ze);


--
-- Name: asocjacja_za_ze con_aso_zz2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_za_ze
    ADD CONSTRAINT con_aso_zz2 FOREIGN KEY (id_zadania) REFERENCES public.zadanie(id_za);


--
-- Name: asocjacja_u_ze con_asocuze1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_u_ze
    ADD CONSTRAINT con_asocuze1 FOREIGN KEY (id_u) REFERENCES public."użytkownik"(id_u);


--
-- Name: asocjacja_u_ze con_asocuze2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.asocjacja_u_ze
    ADD CONSTRAINT con_asocuze2 FOREIGN KEY (id_ze) REFERENCES public."zespół"(id_ze);


--
-- Name: projekt con_proj1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projekt
    ADD CONSTRAINT con_proj1 FOREIGN KEY (typ_projektu) REFERENCES public.typ_projektu(id_t);


--
-- Name: projekt con_proj2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projekt
    ADD CONSTRAINT con_proj2 FOREIGN KEY (admin) REFERENCES public."użytkownik"(id_u) ON DELETE SET NULL;


--
-- Name: projekt con_proj3; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projekt
    ADD CONSTRAINT con_proj3 FOREIGN KEY ("id_zadania_końcowego") REFERENCES public.zadanie(id_za) ON DELETE SET NULL;


--
-- Name: użytkownik con_u; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."użytkownik"
    ADD CONSTRAINT con_u FOREIGN KEY (rola) REFERENCES public.role(id_r);


--
-- Name: zadanie con_za3; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zadanie
    ADD CONSTRAINT con_za3 FOREIGN KEY ("nadrzędny_projekt") REFERENCES public.projekt(id_p) ON DELETE SET NULL;


--
-- Name: zespół con_zesp; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."zespół"
    ADD CONSTRAINT con_zesp FOREIGN KEY (lider) REFERENCES public."użytkownik"(id_u);


--
-- PostgreSQL database dump complete
--

\unrestrict xIV65CE3gfbHyuSWjNnzaMgNLZ4Ire1HPKCL9qN4SgmuGhM1CSI39UdwW0x6PnO

