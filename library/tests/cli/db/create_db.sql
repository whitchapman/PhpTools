CREATE DATABASE testdb;
GRANT ALL PRIVILEGES on testdb.* to testuser@localhost identified by 'testpasswd';

USE testdb;

CREATE TABLE tests (
  test_key int unsigned not null auto_increment,
  created_at timestamp null,
  updated_at timestamp not null default current_timestamp on update current_timestamp,
  test_description varchar(50) not null,
  primary key (test_key)
) default character set utf8;

INSERT INTO tests (created_at, test_description) VALUES
(now(), 'Sample Test #1'),
(now(), 'Sample Test #2B'),
(now(), 'Sample Test #3f'),
(now(), 'Process Test #4'),
(now(), 'Integrity Test #5');

