#CREATE DATABASE <database>;
#GRANT ALL PRIVILEGES on <database>.* to <user>@localhost identified by '<password>';

#USE <database>;

drop table if exists test;

CREATE TABLE test (
  test_key int unsigned not null auto_increment,
  created_at timestamp null,
  updated_at timestamp not null default current_timestamp on update current_timestamp,
  test_description varchar(50) not null,
  test_status bigint not null default 0,
  primary key (test_key)
) default character set utf8;

INSERT INTO test (created_at, test_description) VALUES
(now(), 'Sample Test #1'),
(now(), 'Sample Test #2B'),
(now(), 'Sample Test #3f');

INSERT INTO test (created_at, test_description, test_status) VALUES
(now(), 'Process Test #4', 1),
(now(), 'Integrity Test #5', 1);
