--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.13
-- Dumped by pg_dump version 9.5.13

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: domicilia_agua_parcela(character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.domicilia_agua_parcela(character) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
DECLARE
mi_parcela ALIAS FOR $1;
v_iban varchar(24);
v_domicilia_bco boolean;

BEGIN	
	SELECT iban_agua INTO v_iban FROM socios WHERE id_parcela = mi_parcela;
	IF v_iban IS NULL OR v_iban='' THEN
		v_domicilia_bco := false;
        ELSE
		v_domicilia_bco := true;
        END IF;
	RETURN v_domicilia_bco;
END;
$_$;


ALTER FUNCTION public.domicilia_agua_parcela(character) OWNER TO postgres;

--
-- Name: domicilia_cuota_parcela(character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.domicilia_cuota_parcela(character) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
DECLARE
mi_parcela ALIAS FOR $1;
v_iban varchar(24);
v_domicilia_bco boolean;

BEGIN	
	SELECT iban_cuota INTO v_iban FROM socios WHERE id_parcela = mi_parcela;
	IF v_iban IS NULL OR v_iban='' THEN
		v_domicilia_bco := false;
        ELSE
		v_domicilia_bco := true;
        END IF;
	RETURN v_domicilia_bco;
END;
$_$;


ALTER FUNCTION public.domicilia_cuota_parcela(character) OWNER TO postgres;

--
-- Name: iva_repercutido(numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.iva_repercutido(numeric) RETURNS numeric
    LANGUAGE plpgsql
    AS $_$
-- subtotal = total / ( 1 + ( iva / 100 ) )
-- total = subtotal * (1 + ( iva / 100 ) )
-- iva_repercutido = ((total/subtotal)-1)*100
DECLARE
        v_total ALIAS FOR $1;
        v_iva integer;
	v_subtotal numeric(7,2);
        v_iva_repercutido numeric(7,2);
BEGIN
        SELECT INTO v_iva iva_agua FROM properties;
	SELECT INTO v_subtotal subtotal_iva(v_total);
--	RAISE NOTICE 'el IVA es %',v_iva;
--	RAISE NOTICE 'el subtotal es %',v_subtotal;
        IF v_iva IS NOT NULL THEN
                --Obtenemos el saldo actual
                --SELECT INTO v_iva_repercutido ((v_total/v_subtotal)-1)*100;
		SELECT INTO v_iva_repercutido v_total - v_subtotal;
                        RETURN v_iva_repercutido;
        ELSE
                RAISE EXCEPTION 'No se encuentra IVA aplicable';
        END IF;
END;
$_$;


ALTER FUNCTION public.iva_repercutido(numeric) OWNER TO postgres;

--
-- Name: subtotal_iva(numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.subtotal_iva(numeric) RETURNS numeric
    LANGUAGE plpgsql
    AS $_$
-- subtotal = total / ( 1 + ( iva / 100 ) )
-- total = base imponible * (1 + ( iva / 100 ) )
-- iva = ((total/subtotal)-1)*100
DECLARE
        v_total ALIAS FOR $1;
        v_iva integer;
        v_subtotal numeric(7,2);
BEGIN
        SELECT INTO v_iva iva_agua FROM properties;

        IF v_iva IS NOT NULL THEN
                --Obtenemos el saldo actual
                SELECT INTO v_subtotal v_total/(1+(v_iva/100::numeric));
                        RETURN v_subtotal;
        ELSE
                RAISE EXCEPTION 'No se encuentra IVA aplicable';
        END IF;
END;
$_$;


ALTER FUNCTION public.subtotal_iva(numeric) OWNER TO postgres;

--
-- Name: num_recibo_agua; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.num_recibo_agua
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.num_recibo_agua OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: agua; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.agua (
    id_parcela character varying(4) NOT NULL,
    fecha date NOT NULL,
    l1 integer NOT NULL,
    l2 integer,
    m3 integer,
    pm3 numeric(4,3),
    ult_modif timestamp with time zone,
    user_modif character varying(40),
    averiado boolean,
    notas text,
    estado character(1) DEFAULT 'A'::bpchar,
    domicilia_bco boolean,
    num_recibo integer DEFAULT nextval('public.num_recibo_agua'::regclass),
    CONSTRAINT agua_estado_chk CHECK ((estado = ANY (ARRAY['A'::bpchar, 'R'::bpchar, 'C'::bpchar])))
);


ALTER TABLE public.agua OWNER TO postgres;

--
-- Name: contadores_riego; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contadores_riego (
    id_contador integer NOT NULL,
    lugar character varying(80) NOT NULL
);


ALTER TABLE public.contadores_riego OWNER TO postgres;

--
-- Name: cuotas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cuotas (
    id_parcela character varying(4) NOT NULL,
    fecha date NOT NULL,
    cuota numeric(6,2),
    notas text,
    dto numeric(4,2),
    domicilia_bco boolean,
    estado character(1) NOT NULL,
    CONSTRAINT cuotas_estado_chk CHECK ((estado = ANY (ARRAY['R'::bpchar, 'C'::bpchar])))
);


ALTER TABLE public.cuotas OWNER TO postgres;

--
-- Name: socios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.socios (
    id_parcela character varying(4) NOT NULL,
    titular character varying(80) NOT NULL,
    titular_cc_agua character varying(80),
    cc_agua character varying(20),
    titular_cc_cuota character varying(80),
    cc_cuota character varying(80),
    email character varying(80),
    domicilio character varying(80),
    localidad character varying(80),
    telef1 character varying(9),
    telef2 character varying(9),
    telef3 character varying(10),
    cp character varying(5),
    notas text,
    bic_agua character varying(11),
    bic_cuota character varying(11),
    iban_agua character varying(24),
    iban_cuota character varying(24),
    email2 character varying(80),
    titular2 character varying(80)
);


ALTER TABLE public.socios OWNER TO postgres;

--
-- Name: vista_agua; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_agua AS
 SELECT a.id_parcela,
    s.titular,
    s.titular_cc_agua AS titular_cc,
    s.cc_agua AS cc,
    s.bic_agua AS bic,
    s.iban_agua AS iban,
    a.fecha,
    a.l1,
    a.l2,
    a.m3,
    a.pm3,
    round(((a.m3)::numeric * a.pm3), 2) AS importe,
    a.domicilia_bco,
        CASE
            WHEN (a.domicilia_bco IS FALSE) THEN (0)::numeric
            ELSE round(((a.m3)::numeric * a.pm3), 2)
        END AS domiciliado,
    a.averiado,
    a.notas,
    a.estado
   FROM (public.agua a
     JOIN public.socios s ON (((a.id_parcela)::text = (s.id_parcela)::text)));


ALTER TABLE public.vista_agua OWNER TO postgres;

--
-- Name: estadistica_agua; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.estadistica_agua AS
 SELECT vista_agua.fecha,
    sum(vista_agua.m3) AS m3,
    max(vista_agua.m3) AS max,
    min(vista_agua.m3) AS min,
    round(avg(vista_agua.m3), 2) AS avg,
    round(stddev_pop(vista_agua.m3), 2) AS stddev,
    sum(vista_agua.importe) AS importe,
    sum(vista_agua.domiciliado) AS domiciliado
   FROM public.vista_agua
  WHERE (vista_agua.m3 > 0)
  GROUP BY vista_agua.fecha;


ALTER TABLE public.estadistica_agua OWNER TO postgres;

--
-- Name: vista_agua_parcela; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_agua_parcela AS
 SELECT agua.id_parcela,
    agua.fecha,
    date_part('quarter'::text, agua.fecha) AS trimestre,
    agua.m3
   FROM public.agua;


ALTER TABLE public.vista_agua_parcela OWNER TO postgres;

--
-- Name: estadistica_agua_parcela; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.estadistica_agua_parcela AS
 SELECT vista_agua_parcela.id_parcela,
    vista_agua_parcela.trimestre,
    max(vista_agua_parcela.m3) AS max,
    min(vista_agua_parcela.m3) AS min,
    round(avg(vista_agua_parcela.m3), 2) AS avg,
    round(stddev_pop(vista_agua_parcela.m3), 2) AS stddev
   FROM public.vista_agua_parcela
  GROUP BY vista_agua_parcela.id_parcela, vista_agua_parcela.trimestre
  ORDER BY vista_agua_parcela.id_parcela, vista_agua_parcela.trimestre;


ALTER TABLE public.estadistica_agua_parcela OWNER TO postgres;

--
-- Name: vista_cuotas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_cuotas AS
 SELECT c.id_parcela,
    s.titular,
    s.titular_cc_cuota AS titular_cc,
    s.cc_cuota AS cc,
    s.bic_cuota AS bic,
    s.iban_cuota AS iban,
    c.fecha,
    c.cuota,
    c.dto,
    c.domicilia_bco,
        CASE
            WHEN (c.domicilia_bco IS FALSE) THEN (0)::numeric
            ELSE round((c.cuota - ((c.cuota * c.dto) / (100)::numeric)), 2)
        END AS domiciliado,
    c.estado
   FROM (public.cuotas c
     JOIN public.socios s ON (((c.id_parcela)::text = (s.id_parcela)::text)));


ALTER TABLE public.vista_cuotas OWNER TO postgres;

--
-- Name: estadistica_cuotas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.estadistica_cuotas AS
 SELECT vista_cuotas.fecha,
    sum(vista_cuotas.cuota) AS cuota,
    sum(vista_cuotas.domiciliado) AS domiciliado
   FROM public.vista_cuotas
  GROUP BY vista_cuotas.fecha;


ALTER TABLE public.estadistica_cuotas OWNER TO postgres;

--
-- Name: riego; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.riego (
    id_contador integer NOT NULL,
    fecha date NOT NULL,
    l1 integer,
    l2 integer,
    m3 integer,
    averiado boolean,
    notas text,
    estado character(1) DEFAULT 'A'::bpchar,
    CONSTRAINT riego_chk CHECK ((estado = ANY (ARRAY['A'::bpchar, 'R'::bpchar, 'C'::bpchar])))
);


ALTER TABLE public.riego OWNER TO postgres;

--
-- Name: estadistica_riego; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.estadistica_riego AS
 SELECT riego.fecha,
    sum(riego.m3) AS m3,
    max(riego.m3) AS max,
    min(riego.m3) AS min,
    round(avg(riego.m3), 2) AS avg
   FROM public.riego
  GROUP BY riego.fecha;


ALTER TABLE public.estadistica_riego OWNER TO postgres;

--
-- Name: properties; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.properties (
    id_presentador character varying(16),
    nombre_presentador character varying(70),
    id_acreedor character varying(16),
    nombre_acreedor character varying(70),
    iban_acreedor character(24),
    ref_identificativa character varying(13),
    entidad_receptora character(4),
    oficina_receptora character(4),
    iva_agua integer
);


ALTER TABLE public.properties OWNER TO postgres;

--
-- Name: remesas_especiales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.remesas_especiales (
    id_parcela character varying(12) NOT NULL,
    titular character varying(80) NOT NULL,
    bic character varying(11) NOT NULL,
    iban character(24) NOT NULL,
    importe numeric(6,2) NOT NULL,
    concepto character varying(140) NOT NULL
);


ALTER TABLE public.remesas_especiales OWNER TO postgres;

--
-- Name: ruta_lectura; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ruta_lectura (
    orden integer,
    id_parcela character(3) NOT NULL
);


ALTER TABLE public.ruta_lectura OWNER TO postgres;

--
-- Name: user_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_seq OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    user_id integer DEFAULT nextval('public.user_seq'::regclass) NOT NULL,
    name character varying(50) NOT NULL,
    email character varying(60) NOT NULL,
    password character varying(60),
    social_id character varying(100),
    picture character varying(250),
    created timestamp without time zone DEFAULT now()
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: vista_agua_iva; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_agua_iva AS
 SELECT a.num_recibo,
    a.id_parcela,
    s.titular,
    a.fecha,
    public.subtotal_iva(((a.m3)::numeric * a.pm3)) AS subtotal,
    public.iva_repercutido(((a.m3)::numeric * a.pm3)) AS iva_repercutido,
    round(((a.m3)::numeric * a.pm3), 2) AS total
   FROM (public.agua a
     JOIN public.socios s ON (((a.id_parcela)::text = (s.id_parcela)::text)));


ALTER TABLE public.vista_agua_iva OWNER TO postgres;

--
-- Name: vista_lectura; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_lectura AS
 SELECT r.orden,
    r.id_parcela,
    a.titular,
    a.fecha,
    a.l1,
    a.l2,
    a.averiado,
    a.notas
   FROM (public.ruta_lectura r
     JOIN public.vista_agua a ON ((r.id_parcela = (a.id_parcela)::bpchar)));


ALTER TABLE public.vista_lectura OWNER TO postgres;

--
-- Name: vista_socios_titulares; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vista_socios_titulares AS
 SELECT socios.id_parcela,
    (((socios.titular)::text || ' '::text) || (COALESCE(socios.titular2, ''::character varying))::text) AS titular
   FROM public.socios;


ALTER TABLE public.vista_socios_titulares OWNER TO postgres;


--
-- Name: num_recibo_agua; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.num_recibo_agua', 1, false);

--
-- Name: user_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_seq', 1, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (user_id, name, email, password, social_id, picture, created) FROM stdin;
1	admin	admin@myopenrarp.com	439d5eb8d783745acf89b86157c5c8f6	\N	\N	2018-06-26 12:54:32.097703
\.


--
-- Name: cuota_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cuotas
    ADD CONSTRAINT cuota_pk PRIMARY KEY (id_parcela, fecha);


--
-- Name: fac_agua_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agua
    ADD CONSTRAINT fac_agua_pk PRIMARY KEY (id_parcela, fecha);


--
-- Name: riego_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.riego
    ADD CONSTRAINT riego_pk PRIMARY KEY (id_contador, fecha);


--
-- Name: ruta_lectura_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ruta_lectura
    ADD CONSTRAINT ruta_lectura_pk PRIMARY KEY (id_parcela);


--
-- Name: socios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.socios
    ADD CONSTRAINT socios_pkey PRIMARY KEY (id_parcela);


--
-- Name: ubicacion_riego_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contadores_riego
    ADD CONSTRAINT ubicacion_riego_pkey PRIMARY KEY (id_contador);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- Name: agua_socios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agua
    ADD CONSTRAINT agua_socios_fk FOREIGN KEY (id_parcela) REFERENCES public.socios(id_parcela) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: cuota_socios_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cuotas
    ADD CONSTRAINT cuota_socios_fk FOREIGN KEY (id_parcela) REFERENCES public.socios(id_parcela) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: riego_contadores_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.riego
    ADD CONSTRAINT riego_contadores_fk FOREIGN KEY (id_contador) REFERENCES public.contadores_riego(id_contador) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

