-- ============================================================
-- Staff soft-disable support for citybxta_citytechstore
-- ============================================================
-- Additive only. products.uploaded_by and sales.worker_id both
-- reference users.id with ON DELETE RESTRICT, so hard-deleting any
-- staff member with real history is already impossible at the DB
-- level. is_active lets staff management "remove" someone without
-- deleting the row, preserving that history. Existing rows default
-- to active (1), so no one is locked out by this migration.
-- ============================================================

USE citybxta_citytechstore;

ALTER TABLE users
    ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER role;
