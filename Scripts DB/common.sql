/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     6/8/2023 5:54:37 PM                          */
/*==============================================================*/

/*==============================================================*/
/* Table: CLIENT                                                */
/*==============================================================*/
create table common.CLIENT (
   CLIENT_ID            SERIAL               not null,
   CLIENT_RUC           VARCHAR(32)          null,
   CLIENT_NAME          VARCHAR(64)          null,
   STATUS_ID            INT4                 null,
   constraint PK_CLIENT primary key (CLIENT_ID)
);

/*==============================================================*/
/* Index: CLIENT_PK                                             */
/*==============================================================*/
create unique index CLIENT_PK on common.CLIENT (
CLIENT_ID
);

/*==============================================================*/
/* Index: REFERENCE_CLIENT_FK                                   */
/*==============================================================*/
create  index REFERENCE_CLIENT_FK on common.CLIENT (
CLIENT_STATUS
);

/*==============================================================*/
/* Table: DEPARTMENT                                            */
/*==============================================================*/
create table common.DEPARTMENT (
   DEPARTMENT_ID        SERIAL               not null,
   CLIENT_ID            INT4                 null,
   DEPARTMENT_NAME      VARCHAR(32)             null,
   FATHER_ID            INT4                 null,
   DEPARTMENT_STATUS_ID INT4                 null,
   constraint PK_DEPARTMENT primary key (DEPARTMENT_ID)
);

/*==============================================================*/
/* Index: DEPARTMENT_PK                                         */
/*==============================================================*/
create unique index DEPARTMENT_PK on common.DEPARTMENT (
DEPARTMENT_ID
);

/*==============================================================*/
/* Index: CLIENT_DEPARTMENT_FK                                  */
/*==============================================================*/
create  index CLIENT_DEPARTMENT_FK on common.DEPARTMENT (
CLIENT_ID
);

/*==============================================================*/
/* Index: DEPARTMENT_REFERENCE_FK                               */
/*==============================================================*/
create  index DEPARTMENT_REFERENCE_FK on common.DEPARTMENT (
DEPARTMENT_STATUS_ID
);

/*==============================================================*/
/* Table: HISTORY                                               */
/*==============================================================*/
create table common.HISTORY (
   HISTORY_ID           SERIAL               not null,
   HISTORY_OLD_VALUE    VARCHAR(2048)        null,
   HISTORY_NEW_VALUE    VARCHAR(2048)        null,
   HISTORY_DATE         DATE                 null,
   HISTORY_ACTION       VARCHAR(32)          null,
   HISTORY_USER         VARCHAR(64)          null,
   constraint PK_HISTORY primary key (HISTORY_ID)
);

/*==============================================================*/
/* Index: HISTORY_PK                                            */
/*==============================================================*/
create unique index HISTORY_PK on common.HISTORY (
HISTORY_ID
);

/*==============================================================*/
/* Table: REFERENCE                                             */
/*==============================================================*/
create table common.REFERENCE (
   REFERENCE_ID         SERIAL               not null,
   REFERENCE_NAME       VARCHAR(64)          null,
   REFERENCE_DESCRIPTION VARCHAR(128)         null,
   REFERENCE_TABLE_NAME VARCHAR(64)          null,
   REFERENCE_FIELD      VARCHAR(64)          null,
   constraint PK_REFERENCE primary key (REFERENCE_ID)
);

/*==============================================================*/
/* Index: REFERENCE_PK                                          */
/*==============================================================*/
create unique index REFERENCE_PK on common.REFERENCE (
REFERENCE_ID
);

alter table common.CLIENT
   add constraint FK_CLIENT_REFERENCE_REFERENC foreign key (CLIENT_STATUS)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table common.DEPARTMENT
   add constraint FK_DEPARTME_CLIENT_DE_CLIENT foreign key (CLIENT_ID)
      references common.CLIENT (CLIENT_ID)
      on delete restrict on update restrict;

alter table common.DEPARTMENT
   add constraint FK_DEPARTME_DEPARTMEN_REFERENC foreign key (DEPARTMENT_STATUS_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

