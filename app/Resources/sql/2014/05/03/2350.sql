ALTER TABLE user_description
    ADD COLUMN rating_quantity INT NOT NULL DEFAULT 0;

ALTER TABLE user_description
    RENAME COLUMN total_rating TO rating_score;