ALTER TABLE content
    DROP COLUMN rate,
    ADD COLUMN vote_up INT NOT NULL DEFAULT 0,
    ADD COLUMN vote_down INT NOT NULL DEFAULT 0;

ALTER TABLE user_description
    ADD COLUMN total_rating INT NOT NULL DEFAULT 0;

ALTER TABLE user_modules
    ADD COLUMN total_contents INT NOT NULL DEFAULT 0;