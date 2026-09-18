-- ============================================================
-- Google OAuth support for citybxta_citytechstore
-- ============================================================
-- Additive only. customers.password stays NOT NULL — Google-only
-- accounts get a random, never-disclosed placeholder hash instead of
-- a nullable password column (see Customer::registerWithGoogle()).
-- google_id is nullable+unique: normal password accounts leave it
-- NULL (MySQL allows multiple NULLs under a UNIQUE index), Google
-- accounts store the stable Google "sub" claim.
-- ============================================================

USE citybxta_citytechstore;

ALTER TABLE customers
    ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER phone_number;
