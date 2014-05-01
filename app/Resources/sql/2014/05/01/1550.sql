ALTER TABLE user_modules ADD COLUMN id SERIAL;
ALTER TABLE user_modules ALTER COLUMN status SET DEFAULT 'D';

ALTER TABLE user_modules
  DROP CONSTRAINT user_modules_pkey;

ALTER TABLE user_modules
  ADD CONSTRAINT user_modules_pkey PRIMARY KEY(id);