DROP DATABASE IF EXISTS veille_db;
CREATE DATABASE veille_db;

\c veille_db;

CREATE TYPE user_role AS ENUM ('student', 'teacher');
CREATE TYPE subject_status AS ENUM ('pending', 'approved', 'rejected');
CREATE TYPE presentation_status AS ENUM ('scheduled', 'completed', 'cancelled');
CREATE TYPE account_status AS ENUM ('pending', 'active', 'inactive');

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role user_role NOT NULL,
    account_status account_status DEFAULT 'pending',
    reset_token VARCHAR(255),
    reset_token_expiry TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subjects (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    suggested_by INTEGER REFERENCES users(id),
    status subject_status DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE presentations (
    id SERIAL PRIMARY KEY,
    subject_id INTEGER REFERENCES subjects(id),
    scheduled_date TIMESTAMP NOT NULL,
    status presentation_status DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE student_presentations (
    id SERIAL PRIMARY KEY,
    student_id INTEGER REFERENCES users(id),
    presentation_id INTEGER REFERENCES presentations(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, presentation_id)
);

CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

