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
    avatar_url VARCHAR(255),
    location VARCHAR(100),
    website VARCHAR(255),
    signature TEXT,
    role VARCHAR(20) DEFAULT 'user',
    reputation INTEGER DEFAULT 0,
    is_banned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP
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
INSERT INTO users (
        username,
        email,
        password_hash,
        role,
        bio,
        signature
    )
VALUES (
        'Admin',
        'admin@gruppo03.it',
        'pass_criptata',
        'admin',
        'Il capo',
        'Staff Gruppo03'
    ),
    (
        'Mario',
        'mario@email.it',
        'pass_criptata',
        'user',
        'Utente nuovo',
        'Ciao a tutti!'
    ),
    (
        'Luigi',
        'luigi@email.it',
        'pass_criptata',
        'moderator',
        'Modero le discussioni',
        NULL
    );
INSERT INTO categories (name, slug, description, sort_order)
VALUES ('Annunci', 'annunci', 'News', 1),
    ('Hardware', 'hardware', 'PC Hardware', 2);
INSERT INTO threads (
        category_id,
        user_id,
        title,
        slug,
        created_at,
        last_activity_at
    )
VALUES (1, 1, 'Benvenuti', 'benvenuti', NOW(), NOW());
INSERT INTO posts (thread_id, user_id, parent_id, content)
VALUES (1, 1, NULL, 'Primo post del forum!');