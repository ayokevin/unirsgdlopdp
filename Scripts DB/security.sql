/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     6/8/2023 6:23:26 PM                          */
/*==============================================================*/


/*==============================================================*/
/* Table: OPTION                                                */
/*==============================================================*/
create table security.OPTION (
   OPTIONS_ID           SERIAL               not null,
   OPTION_TYPE          INT4                 null,
   FATHER_OPTION        INT4                 null,
   OPTION_NAME          VARCHAR(32)          null,
   OPTION_COMPONENT     VARCHAR(1024)        null,
   OPTION_ORDER         INT4                 null,
   constraint PK_OPTION primary key (OPTIONS_ID)
);

/*==============================================================*/
/* Index: OPTION_PK                                             */
/*==============================================================*/
create unique index OPTION_PK on security.OPTION (
OPTIONS_ID
);


/*==============================================================*/
/* Table: ROL                                                   */
/*==============================================================*/
create table security.ROL (
   ROL_ID               SERIAL               not null,
   STATUS_ID            INT4                 null,
   ROL_NAME             VARCHAR(32)          not null,
   ROL_DESCRIPTION      VARCHAR(258)         not null,
   constraint PK_ROL primary key (ROL_ID)
);

/*==============================================================*/
/* Index: ROL_PK                                                */
/*==============================================================*/
create unique index ROL_PK on security.ROL (
ROL_ID
);

/*==============================================================*/
/* Index: REFERENCE_ROL_FK                                      */
/*==============================================================*/
create  index REFERENCE_ROL_FK on security.ROL (
STATUS_ID
);

/*==============================================================*/
/* Table: ROL_OPTION                                            */
/*==============================================================*/
create table security.ROL_OPTION (
   ROL_OPTION_ID        SERIAL               not null,
   OPTIONS_ID           INT4                 not null,
   ROL_ID               INT4                 not null,
   STATUS_ID            INT4                 null,
   constraint PK_ROL_OPTION primary key (ROL_OPTION_ID, OPTIONS_ID, ROL_ID)
);

/*==============================================================*/
/* Index: ROL_OPTION_PK                                         */
/*==============================================================*/
create unique index ROL_OPTION_PK on security.ROL_OPTION (
ROL_OPTION_ID,
OPTIONS_ID,
ROL_ID
);

/*==============================================================*/
/* Index: ROL_OPTION2_FK                                        */
/*==============================================================*/
create  index ROL_OPTION2_FK on security.ROL_OPTION (
ROL_ID
);

/*==============================================================*/
/* Index: ROL_OPTION_FK                                         */
/*==============================================================*/
create  index ROL_OPTION_FK on security.ROL_OPTION (
OPTIONS_ID
);

/*==============================================================*/
/* Index: REF_ROL_OPTION_FK                                     */
/*==============================================================*/
create  index REF_ROL_OPTION_FK on security.ROL_OPTION (
STATUS_ID
);

/*==============================================================*/
/* Table: ROL_USER_SEC                                          */
/*==============================================================*/
create table security.ROL_USER_SEC (
   ROL_USER_SEC_ID      SERIAL                 not null,
   USER_SEC_ID          INT4                 not null,
   ROL_ID               INT4                 not null,
   STATUS_ID            INT4                 null,
   constraint PK_ROL_USER_SEC primary key (USER_SEC_ID, ROL_ID, ROL_USER_SEC_ID)
);

/*==============================================================*/
/* Index: ROL_USER_SEC_PK                                       */
/*==============================================================*/
create unique index ROL_USER_SEC_PK on security.ROL_USER_SEC (
USER_SEC_ID,
ROL_ID,
ROL_USER_SEC_ID
);

/*==============================================================*/
/* Index: ROL_USER_SEC2_FK                                      */
/*==============================================================*/
create  index ROL_USER_SEC2_FK on security.ROL_USER_SEC (
ROL_ID
);

/*==============================================================*/
/* Index: ROL_USER_SEC_FK                                       */
/*==============================================================*/
create  index ROL_USER_SEC_FK on security.ROL_USER_SEC (
USER_SEC_ID
);

/*==============================================================*/
/* Index: REF_ROL_USER_SEC_FK                                   */
/*==============================================================*/
create  index REF_ROL_USER_SEC_FK on security.ROL_USER_SEC (
STATUS_ID
);

/*==============================================================*/
/* Table: USER_SEC                                              */
/*==============================================================*/
create table security.USER_SEC (
   USER_SEC_ID          SERIAL               not null,
   STATUS_ID            INT4                 null,
   USER_EMAIL           VARCHAR(64)          null,
   USER_PASSWORD        VARCHAR(64)          null,
   USER_FIRST_NAME      VARCHAR(64)          null,
   USER_LAST_NAME       VARCHAR(64)          null,
   USER_APPLICATION     BOOL                 null,
   CLIENT_ID            INT4                 not null,
   constraint PK_USER_SEC primary key (USER_SEC_ID)
);

/*==============================================================*/
/* Index: USER_SEC_PK                                           */
/*==============================================================*/
create unique index USER_SEC_PK on security.USER_SEC (
USER_SEC_ID
);

/*==============================================================*/
/* Index: REFERENCE_USER_SEC_FK                                 */
/*==============================================================*/
create  index REFERENCE_USER_SEC_FK on security.USER_SEC (
STATUS_ID
);

/*==============================================================*/
/* Table: USER_SEC_DEPARTMENT                                   */
/*==============================================================*/
create table security.USER_SEC_DEPARTMENT (
   USER_SEC_DEPARTMENT_ID SERIAL               not null,
   DEPARTMENT_ID        INT4                 not null,
   USER_SEC_ID          INT4                 not null,
   STATUS_ID            INT4                 null,
   constraint PK_USER_SEC_DEPARTMENT primary key (DEPARTMENT_ID, USER_SEC_ID, USER_SEC_DEPARTMENT_ID)
);

/*==============================================================*/
/* Index: USER_SEC_DEPARTMENT_PK                                */
/*==============================================================*/
create unique index USER_SEC_DEPARTMENT_PK on security.USER_SEC_DEPARTMENT (
DEPARTMENT_ID,
USER_SEC_ID,
USER_SEC_DEPARTMENT_ID
);

/*==============================================================*/
/* Index: USER_SEC_DEPARTMENT2_FK                               */
/*==============================================================*/
create  index USER_SEC_DEPARTMENT2_FK on security.USER_SEC_DEPARTMENT (
USER_SEC_ID
);

/*==============================================================*/
/* Index: USER_SEC_DEPARTMENT_FK                                */
/*==============================================================*/
create  index USER_SEC_DEPARTMENT_FK on security.USER_SEC_DEPARTMENT (
DEPARTMENT_ID
);

/*==============================================================*/
/* Index: REF_USER_SEC_DEPARTMENT_FK                            */
/*==============================================================*/
create  index REF_USER_SEC_DEPARTMENT_FK on security.USER_SEC_DEPARTMENT (
STATUS_ID
);

alter table security.ROL
   add constraint FK_ROL_REFERENCE_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table security.ROL_OPTION
   add constraint FK_ROL_OPTI_REF_ROL_O_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table security.ROL_OPTION
   add constraint FK_ROL_OPTI_ROL_OPTIO_OPTION foreign key (OPTIONS_ID)
      references security.OPTION (OPTIONS_ID)
      on delete restrict on update restrict;

alter table security.ROL_OPTION
   add constraint FK_ROL_OPTI_ROL_OPTIO_ROL foreign key (ROL_ID)
      references security.ROL (ROL_ID)
      on delete restrict on update restrict;

alter table security.ROL_USER_SEC
   add constraint FK_ROL_USER_REF_ROL_U_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table security.ROL_USER_SEC
   add constraint FK_ROL_USER_ROL_USER__USER_SEC foreign key (USER_SEC_ID)
      references security.USER_SEC (USER_SEC_ID)
      on delete restrict on update restrict;

alter table security.ROL_USER_SEC
   add constraint FK_ROL_USER_ROL_USER__ROL foreign key (ROL_ID)
      references security.ROL (ROL_ID)
      on delete restrict on update restrict;

alter table security.USER_SEC
   add constraint FK_USER_SEC_REFERENCE_REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table security.USER_SEC_DEPARTMENT
   add constraint FK_USER_SEC_REF_USER__REFERENC foreign key (STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table security.USER_SEC_DEPARTMENT
   add constraint FK_USER_SEC_USER_SEC__DEPARTME foreign key (DEPARTMENT_ID)
      references common.DEPARTMENT (DEPARTMENT_ID)
      on delete restrict on update restrict;

alter table security.USER_SEC_DEPARTMENT
   add constraint FK_USER_SEC_USER_SEC__USER_SEC foreign key (USER_SEC_ID)
      references security.USER_SEC (USER_SEC_ID)
      on delete restrict on update restrict;

ALTER TABLE security.USER_SEC 
ADD CONSTRAINT client_id_fk
FOREIGN KEY (client_id) REFERENCES common.client(client_id);