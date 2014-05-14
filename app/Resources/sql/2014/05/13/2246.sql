ALTER TABLE module
  ADD COLUMN short_name VARCHAR(25);

UPDATE module
  SET short_name = 'blog'
  WHERE name = 'GitBlog';

UPDATE module
  SET short_name = 'tube'
  WHERE name = 'GitTube';

UPDATE module
  SET short_name = 'drive'
  WHERE name = 'GitDrive';

DELETE FROM module
  WHERE name = 'none';