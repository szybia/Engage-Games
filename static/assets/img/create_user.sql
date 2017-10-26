-- Create a user with username patricia and password obyrne
--NOTE:  You can see your tablespaces by running 
--SELECT TABLESPACE_NAME from DBA_TABLESPACES - it'll probably be USERS
create user &&USERNAME
identified by &PASSWORD
default tablespace &&tbsp
quota unlimited on &&tbsp;
GRANT CREATE SESSION,
      CREATE TABLE, 
      CREATE VIEW,
      CREATE procedure,
      CREATE SEQUENCE,
      CREATE TRIGGER to &&username;
undefine password;
undefine tbsp;
undefine username;
--alter user doreilly identified by C13469208;