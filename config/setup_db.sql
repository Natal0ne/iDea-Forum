-- =========================================================
-- FILE MASTER DI SVILUPPO: setup_db.sql
-- ESEGUIRE DENTRO IL DATABASE 'gruppo03'
-- =========================================================
-- Pulizia preventiva (DROP delle tabelle se esistono, per ripartire da zero)
DROP TABLE IF EXISTS post_votes CASCADE;
DROP TABLE IF EXISTS post_attachments CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS threads CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS user_settings CASCADE;
DROP TABLE IF EXISTS users CASCADE;
-- =========================================================
--  CREAZIONE TABELLE
-- =========================================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    bio TEXT,
    avatar_url VARCHAR(255) DEFAULT 'assets/img/default-avatar.png',
    location VARCHAR(100),
    website VARCHAR(255),
    signature TEXT,
    role VARCHAR(20) DEFAULT 'user',
    reputation INTEGER DEFAULT 0,
    is_banned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_active_at TIMESTAMP
);
-- Assicuriamoci che l'owner sia www
ALTER TABLE users OWNER TO www;
CREATE TABLE user_settings (
    user_id INTEGER PRIMARY KEY,
    theme VARCHAR(20) DEFAULT 'light',
    language VARCHAR(5) DEFAULT 'en',
    receive_newsletter BOOLEAN DEFAULT TRUE,
    email_notifications BOOLEAN DEFAULT TRUE,
    show_signatures BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_settings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
ALTER TABLE user_settings OWNER TO www;
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE,
    description VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    parent_id INTEGER,
    CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE
    SET NULL
);
ALTER TABLE categories OWNER TO www;
CREATE TABLE threads (
    id SERIAL PRIMARY KEY,
    category_id INTEGER NOT NULL,
    user_id INTEGER,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    is_locked BOOLEAN DEFAULT FALSE,
    is_sticky BOOLEAN DEFAULT FALSE,
    view_count INTEGER DEFAULT 0,
    reply_count INTEGER DEFAULT 0,
    last_activity_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_thread_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    CONSTRAINT fk_thread_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE
    SET NULL
);
ALTER TABLE threads OWNER TO www;
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    thread_id INTEGER NOT NULL,
    user_id INTEGER,
    parent_id INTEGER,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    ip_address VARCHAR(45),
    CONSTRAINT fk_post_thread FOREIGN KEY (thread_id) REFERENCES threads(id) ON DELETE CASCADE,
    CONSTRAINT fk_post_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE
    SET NULL,
        CONSTRAINT fk_post_parent FOREIGN KEY (parent_id) REFERENCES posts(id) ON DELETE CASCADE
);
ALTER TABLE posts OWNER TO www;
CREATE TABLE post_attachments (
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL,
    file_url VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    file_size INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attachment_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
ALTER TABLE post_attachments OWNER TO www;
CREATE TABLE post_votes (
    user_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    vote_value INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, post_id),
    CONSTRAINT fk_vote_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_vote_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
ALTER TABLE post_votes OWNER TO www;

-- =========================================================
--  POPOLAMENTO DATI (INSERT DI PROVA)
-- =========================================================


BEGIN;

INSERT INTO public.users (
id, username, email, password_hash, bio, location, website, signature, last_active_at) VALUES (
'1'::integer, 'admin'::character varying, 'admin@idea.com'::character varying, 'admin'::character varying, 'Sono l''admin di iDea!'::text, NULL::character varying, NULL::character varying, NULL::text, NULL::timestamp without time zone)
 returning id;

INSERT INTO categories (
    id, name, slug, description, sort_order, parent_id
)
VALUES 
(1, 'Informations', 'informations', 'Some informations&rules about iDea', 0, NULL );

COMMIT;


BEGIN: 

INSERT INTO threads 
(category_id, user_id, title, slug, is_locked, is_sticky, view_count, reply_count, last_activity_at, created_at)
VALUES
(1, 1, 'iDea Rules', 'rules', true, false, 0, 0, NOW(), NOW())
RETURNING id;

INSERT INTO posts
(thread_id, user_id, content, created_at)
VALUES
(1, 1,
'1. Respect other members
2. No spam
3. No illegal content
4. No hate speech
5. Follow moderators instructions',
NOW());

COMMIT;


BEGIN;

INSERT INTO threads 
(category_id, user_id, title, slug, is_locked, is_sticky, view_count, reply_count, last_activity_at, created_at)
VALUES
(1, 1, 'iDea Privacy Policy', 'privacy', true, false, 0, 0, NOW(), NOW())
RETURNING id;

INSERT INTO posts
(thread_id, user_id, content, created_at)
VALUES
(1, 1,
'At iDea, we believe that privacy is a right. 
        We want to empower our users to be the masters of their identity. In this privacy policy, 
        we want to help you understand how and why iDea collects, uses, 
        information about you when you use our websites, widgets, APIs and emails.
        We want this privacy policy to empower you to make better choices about how you use iDea. 
        We''d love for you to read the whole policy',
NOW());

COMMIT;

BEGIN;

INSERT INTO threads 
(category_id, user_id, title, slug, is_locked, is_sticky, view_count, reply_count, last_activity_at, created_at)
VALUES
(1, 1, 'FAQ', 'faq', true, false, 0, 0, NOW(), NOW())
RETURNING id;

INSERT INTO posts
(thread_id, user_id, content, created_at)
VALUES
(1, 1,
'Q: How do I change username?
A: Go to profile settings.

Q: How can I search threads?
A: Use the search bar at the top of the page. ',
NOW());

COMMIT;


