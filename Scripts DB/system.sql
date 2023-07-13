/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     6/20/2023 10:35:58 AM                        */
/*==============================================================*/
/*==============================================================*/
/* Table: ACTION                                                */
/*==============================================================*/
create table system.ACTION (
   ACTION_ID            SERIAL               not null,
   STATUS_ID            INT4                 null,
   ACTION_NAME          VARCHAR(64)          null,
   ACTION_DESCRIPTION   VARCHAR(1024)        null,
   DEPARTMENT_ID        INT4                 null,
   ACTION_CREATED_AT    TIMESTAMP            null,
   ACTION_UPDATED_AT    TIMESTAMP            null,
   
   constraint PK_ACTION primary key (ACTION_ID)
);

/*==============================================================*/
/* Index: ACTION_PK                                             */
/*==============================================================*/
create unique index ACTION_PK on system.ACTION (
ACTION_ID
);

/*==============================================================*/
/* Index: REFERENCE_ACTION_FK                                   */
/*==============================================================*/
create  index REFERENCE_ACTION_FK on system.ACTION (
STATUS_ID
);

/*==============================================================*/
/* Index: DEPARTMENT_ACTION_FK                                 */
/*==============================================================*/
create  index DEPARTMENT_ACTION_FK on system.ACTION (
DEPARTMENT_ID
);

/*==============================================================*/
/* Table: ARTICLE                                               */
/*==============================================================*/
create table system.ARTICLE (
   ARTICLE_ID           SERIAL               not null,
   PROJECT_ID           INT4                 null,
   ARTICLE_NAME         VARCHAR(32)          null,
   ARTICLE_DESCRIPTION  VARCHAR(1024)        null,
   ARTICLE_FATHER_ID    INT4                 null,
   ARTICLE_APPLY        INT4                 null,
   ARTICLE_OBSERVATION  VARCHAR(1024)        null,
   STATUS_ID            INT4                 null,
   ARTICLE_ACTUAL_STATE INT4                 null,
   ARTICLE_COMPLIANCE   INT4                 null,
   ARTICLE_UPDATE_AT    TIMESTAMP            null,
   constraint PK_ARTICLE primary key (ARTICLE_ID)
);

/*==============================================================*/
/* Index: ARTICLE_PK                                            */
/*==============================================================*/
create unique index ARTICLE_PK on system.ARTICLE (
ARTICLE_ID
);

/*==============================================================*/
/* Index: PROJECT_ARTICLE_FK                                    */
/*==============================================================*/
create  index PROJECT_ARTICLE_FK on system.ARTICLE (
PROJECT_ID
);

/*==============================================================*/
/* Table: ARTICLE_PROCESS                                       */
/*==============================================================*/
create table system.ARTICLE_PROCESS (
   PROCESS_ID           INT4                 not null,
   ARTICLE_ID           INT4                 not null,
   constraint PK_ARTICLE_PROCESS primary key (PROCESS_ID, ARTICLE_ID)
);

/*==============================================================*/
/* Index: ARTICLE_PROCESS_PK                                    */
/*==============================================================*/
create unique index ARTICLE_PROCESS_PK on system.ARTICLE_PROCESS (
PROCESS_ID,
ARTICLE_ID
);

/*==============================================================*/
/* Index: ARTICLE_PROCESS2_FK                                   */
/*==============================================================*/
create  index ARTICLE_PROCESS2_FK on system.ARTICLE_PROCESS (
ARTICLE_ID
);

/*==============================================================*/
/* Index: ARTICLE_PROCESS_FK                                    */
/*==============================================================*/
create  index ARTICLE_PROCESS_FK on system.ARTICLE_PROCESS (
PROCESS_ID
);

/*==============================================================*/
/* Table: FILE                                                  */
/*==============================================================*/
create table system.FILE (
   FILE_ID              SERIAL               not null,
   ARTICLE_ID           INT4                 null,
   PROCESS_ID           INT4                 null,
   ACTION_ID            INT4                 null,
   STATUS_ID            INT4                 null,
   FILE_DATE            TIMESTAMP            null,
   FILE_NAME            VARCHAR(128)         null,
   FILE_NAMED           VARCHAR(128)         null,
   FILE_NAME_DB         VARCHAR(128)         null,
   constraint PK_FILE primary key (FILE_ID)
);

/*==============================================================*/
/* Index: FILE_PK                                               */
/*==============================================================*/
create unique index FILE_PK on system.FILE (
FILE_ID
);

/*==============================================================*/
/* Index: ARTICLE_FILE_FK                                       */
/*==============================================================*/
create  index ARTICLE_FILE_FK on system.FILE (
ARTICLE_ID
);
/*==============================================================*/
/* Index: REFERENCE_FILE_FK                                   */
/*==============================================================*/
create  index REFERENCE_FILE_FK on system.FILE (
STATUS_ID
);

/*==============================================================*/
/* Index: ACTION_ID_FILE_FK                                       */
/*==============================================================*/

create  index ACTION_FILE_FK on system.FILE (
ACTION_ID
);

/*==============================================================*/
/* Index: PROCESS_FILE_FK                                       */
/*==============================================================*/
create  index PROCESS_FILE_FK on system.FILE (
PROCESS_ID
);

/*==============================================================*/
/* Table: PROCESS                                               */
/*==============================================================*/
create table system.PROCESS (
   PROCESS_ID           SERIAL               not null,
   USER_SEC_ID          INT4                 null,
   DEPARTMENT_ID        INT4                 null,
   PROCESS_NAME         VARCHAR(64)          null,
   PROCESS_ORDER        INT4                 null,
   PROCESS_DESCRIPTION  VARCHAR(1024)        null,
   STATUS_ID            INT4                 null,
   constraint PK_PROCESS primary key (PROCESS_ID)
);

/*==============================================================*/
/* Index: PROCESS_PK                                            */
/*==============================================================*/
create unique index PROCESS_PK on system.PROCESS (
PROCESS_ID
);

/*==============================================================*/
/* Index: USER_SEC_PROCESS_FK                                   */
/*==============================================================*/
create  index USER_SEC_PROCESS_FK on system.PROCESS (
USER_SEC_ID
);

/*==============================================================*/
/* Index: DEPARTMENT_PROCESS_FK                                 */
/*==============================================================*/
create  index DEPARTMENT_PROCESS_FK on system.PROCESS (
DEPARTMENT_ID
);

/*==============================================================*/
/* Table: PROCESS_DEPENDENCE                                    */
/*==============================================================*/
create table system.PROCESS_DEPENDENCE (
   PROCESS_DEPENDENCE_ID SERIAL               not null,
   FATHER_ID            INT4                 null,
   CHIELD_ID            INT4                 null,
   constraint PK_PROCESS_DEPENDENCE primary key (PROCESS_DEPENDENCE_ID)
);

/*==============================================================*/
/* Index: PROCESS_DEPENDENCE_PK                                 */
/*==============================================================*/
create unique index PROCESS_DEPENDENCE_PK on system.PROCESS_DEPENDENCE (
PROCESS_DEPENDENCE_ID
);

/*==============================================================*/
/* Table: PRODEPEN_PROCESS                                      */
/*==============================================================*/
create table system.PRODEPEN_PROCESS (
   PRODEPEN_PROCESS_ID  SERIAL               not null,
   PROCESS_DEPENDENCE_ID INT4                 not null,
   PROCESS_ID           INT4                 not null,
   constraint PK_PRODEPEN_PROCESS primary key (PROCESS_DEPENDENCE_ID, PROCESS_ID, PRODEPEN_PROCESS_ID)
);

/*==============================================================*/
/* Index: PRODEPEN_PROCESS_PK                                   */
/*==============================================================*/
create unique index PRODEPEN_PROCESS_PK on system.PRODEPEN_PROCESS (
PROCESS_DEPENDENCE_ID,
PROCESS_ID,
PRODEPEN_PROCESS_ID
);

/*==============================================================*/
/* Index: PRODEPEN_PROCESS2_FK                                  */
/*==============================================================*/
create  index PRODEPEN_PROCESS2_FK on system.PRODEPEN_PROCESS (
PROCESS_ID
);

/*==============================================================*/
/* Index: PRODEPEN_PROCESS_FK                                   */
/*==============================================================*/
create  index PRODEPEN_PROCESS_FK on system.PRODEPEN_PROCESS (
PROCESS_DEPENDENCE_ID
);

/*==============================================================*/
/* Table: PROJECT                                               */
/*==============================================================*/
create table system.PROJECT (
   PROJECT_ID           SERIAL               not null,
   CLIENT_ID            INT4                 null,
   REFERENCE_ID         INT4                 null,
   PROJECT_NAME         VARCHAR(64)          null,
   constraint PK_PROJECT primary key (PROJECT_ID)
);

/*==============================================================*/
/* Index: PROJECT_PK                                            */
/*==============================================================*/
create unique index PROJECT_PK on system.PROJECT (
PROJECT_ID
);

/*==============================================================*/
/* Index: CLIENT_PROJECT_FK                                     */
/*==============================================================*/
create  index CLIENT_PROJECT_FK on system.PROJECT (
CLIENT_ID
);

/*==============================================================*/
/* Index: REFERENCE_PROJECT_FK                                  */
/*==============================================================*/
create  index REFERENCE_PROJECT_FK on system.PROJECT (
REFERENCE_ID
);


/*==============================================================*/
/* Table: ARTICLE_ACTION                                        */
/*==============================================================*/
create table system.ARTICLE_ACTION (
   ACTION_ID            INT4                 not null,
   ARTICLE_ID           INT4                 not null,
   constraint PK_ARTICLE_ACTION primary key (ACTION_ID, ARTICLE_ID, ARTICLE_ACTION)
);

/*==============================================================*/
/* Index: ARTICLE_ACTION_PK                                     */
/*==============================================================*/
create unique index ARTICLE_ACTION_PK on system.ARTICLE_ACTION (
ACTION_ID,
ARTICLE_ID,
);

/*==============================================================*/
/* Index: ARTICLE_ACTION2_FK                                    */
/*==============================================================*/
create  index ARTICLE_ACTION2_FK on system.ARTICLE_ACTION (
ARTICLE_ID
);

/*==============================================================*/
/* Index: ARTICLE_ACTION_FK                                     */
/*==============================================================*/
create  index ARTICLE_ACTION_FK on system.ARTICLE_ACTION (
ACTION_ID
);


/*==============================================================*/
/* Table: ACTION_PROCESS                                        */
/*==============================================================*/
create table system.ACTION_PROCESS (
   PROCESS_ID           INT4                 not null,
   ACTION_ID            INT4                 not null,
   constraint PK_ACTION_PROCESS primary key (PROCESS_ID, ACTION_ID)
);

/*==============================================================*/
/* Index: ACTION_PROCESS_PK                                     */
/*==============================================================*/
create unique index ACTION_PROCESS_PK on system.ACTION_PROCESS (
PROCESS_ID,
ACTION_ID
);

/*==============================================================*/
/* Index: ACTION_PROCESS2_FK                                    */
/*==============================================================*/
create  index ACTION_PROCESS2_FK on system.ACTION_PROCESS (
ACTION_ID
);

/*==============================================================*/
/* Index: ACTION_PROCESS_FK                                     */
/*==============================================================*/
create  index ACTION_PROCESS_FK on system.ACTION_PROCESS (
PROCESS_ID
);


alter table system.ARTICLE
   add constraint FK_ARTICLE_PROJECT_A_PROJECT foreign key (PROJECT_ID)
      references system.PROJECT (PROJECT_ID)
      on delete restrict on update restrict;

alter table system.ARTICLE_PROCESS
   add constraint FK_ARTICLE__ARTICLE_P_PROCESS foreign key (PROCESS_ID)
      references system.PROCESS (PROCESS_ID)
      on delete restrict on update restrict;

alter table system.ARTICLE_PROCESS
   add constraint FK_ARTICLE__ARTICLE_P_ARTICLE foreign key (ARTICLE_ID)
      references system.ARTICLE (ARTICLE_ID)
      on delete restrict on update restrict;

alter table system.FILE
   add constraint FK_FILE_ARTICLE_F_ARTICLE foreign key (ARTICLE_ID)
      references system.ARTICLE (ARTICLE_ID)
      on delete restrict on update restrict;

alter table system.FILE
   add constraint FK_FILE_PROCESS_F_PROCESS foreign key (PROCESS_ID)
      references system.PROCESS (PROCESS_ID)
      on delete restrict on update restrict;

alter table system.PROCESS
   add constraint FK_PROCESS_DEPARTMEN_DEPARTME foreign key (DEPARTMENT_ID)
      references common.DEPARTMENT (DEPARTMENT_ID)
      on delete restrict on update restrict;

alter table system.PROCESS
   add constraint FK_PROCESS_USER_SEC__USER_SEC foreign key (USER_SEC_ID)
      references security.USER_SEC (USER_SEC_ID)
      on delete restrict on update restrict;

alter table system.PRODEPEN_PROCESS
   add constraint FK_PRODEPEN_PRODEPEN__PROCESS_ foreign key (PROCESS_DEPENDENCE_ID)
      references system.PROCESS_DEPENDENCE (PROCESS_DEPENDENCE_ID)
      on delete restrict on update restrict;

alter table system.PRODEPEN_PROCESS
   add constraint FK_PRODEPEN_PRODEPEN__PROCESS foreign key (PROCESS_ID)
      references system.PROCESS (PROCESS_ID)
      on delete restrict on update restrict;

alter table system.PROJECT
   add constraint FK_PROJECT_CLIENT_PR_CLIENT foreign key (CLIENT_ID)
      references common.CLIENT (CLIENT_ID)
      on delete restrict on update restrict;

alter table system.PROJECT
   add constraint FK_PROJECT_REFERENCE_REFERENC foreign key (REFERENCE_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.PROCESS
   add constraint FK_PROCESS_REFERENCE_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.ARTICLE
   add constraint FK_ARTICLE_REFERENCE_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.ACTION
   add constraint FK_ACTION_REFERENCE_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.FILE
   add constraint FK_FILE_ACTION_FI_ACTION foreign key (ACTION_ID)
      references system.ACTION (ACTION_ID)
      on delete restrict on update restrict;

alter table system.ARTICLE_ACTION
   add constraint FK_ARTICLE__ARTICLE_A_ACTION foreign key (ACTION_ID)
      references system.ACTION (ACTION_ID)
      on delete restrict on update restrict;

alter table system.ARTICLE_ACTION
   add constraint FK_ARTICLE__ARTICLE_A_ARTICLE foreign key (ARTICLE_ID)
      references system.ARTICLE (ARTICLE_ID)
      on delete restrict on update restrict;

alter table  system.ARTICLE
   add constraint FK_ARTICLE_REFERENCE_REFERENC foreign key (STATUS_ID)
      references  common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table  system.ARTICLE
   add constraint FK_ARTICLE_REFERENCE2_REFERENC foreign key (ARTICLE_COMPLIANCE)
      references  common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;
      
alter table  system.ARTICLE
   add constraint FK_ARTICLE_REFERENCE3_REFERENC foreign key (ARTICLE_APPLY)
      references  common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.ACTION
   add constraint FK_ACTION_DEPARTMEN_DEPARTME foreign key (DEPARTMENT_ID)
      references common.DEPARTMENT (DEPARTMENT_ID)
      on delete restrict on update restrict;

alter table  system.FILE
   add constraint FK_FILE_REFERENCE_REFERENC foreign key (STATUS_ID)
      references  common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table system.ACTION_PROCESS
   add constraint FK_ACTION_P_ACTION_PR_PROCESS foreign key (PROCESS_ID)
      references  system.PROCESS (PROCESS_ID)
      on delete restrict on update restrict;

alter table  system.ACTION_PROCESS
   add constraint FK_ACTION_P_ACTION_PR_ACTION foreign key (ACTION_ID)
      references  system.ACTION (ACTION_ID)
      on delete restrict on update restrict;