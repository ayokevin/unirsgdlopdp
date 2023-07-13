/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     6/20/2023 9:34:05 AM                         */
/*==============================================================*/

/*==============================================================*/
/* Table: ARTICLE                                               */
/*==============================================================*/
create table project.ARTICLE (
   ARTICLE_ID           SERIAL               not null,
   ARTICLE_NAME         VARCHAR(64)          not null,
   ARTICLE_DESCRIPTION  VARCHAR(128)         not null,
   LAW_ID               INT4                 null,
   LAW_NAME             VARCHAR(32)          null,
   FATHER_ID            INT4                 null,
   REFERENCE_ID         INT4                 null,
   constraint PK_ARTICLE primary key (ARTICLE_ID, ARTICLE_NAME, ARTICLE_DESCRIPTION)
);

/*==============================================================*/
/* Index: ARTICLE_PK                                            */
/*==============================================================*/
create unique index ARTICLE_PK on project.ARTICLE (
ARTICLE_ID,
ARTICLE_NAME,
ARTICLE_DESCRIPTION
);

/*==============================================================*/
/* Index: LAW_ARTICLE_FK                                        */
/*==============================================================*/
create  index LAW_ARTICLE_FK on project.ARTICLE (
LAW_ID,
LAW_NAME
);

/*==============================================================*/
/* Index: REFERENCE_ARTICLE_FK                                  */
/*==============================================================*/
create  index REFERENCE_ARTICLE_FK on project.ARTICLE (
REFERENCE_ID
);

/*==============================================================*/
/* Table: LAW                                                   */
/*==============================================================*/
create table project.LAW (
   LAW_ID               SERIAL               not null,
   LAW_NAME             VARCHAR(32)          not null,
   REFERENCE_ID         INT4                 null,
   constraint PK_LAW primary key (LAW_ID, LAW_NAME)
);

/*==============================================================*/
/* Index: LAW_PK                                                */
/*==============================================================*/
create unique index LAW_PK on project.LAW (
LAW_ID,
LAW_NAME
);

/*==============================================================*/
/* Index: REFERENCE_LAW_FK                                      */
/*==============================================================*/
create  index REFERENCE_LAW_FK on project.LAW (
REFERENCE_ID
);


alter table project.ARTICLE
   add constraint FK_ARTICLE_LAW_ARTIC_LAW foreign key (LAW_ID, LAW_NAME)
      references project.LAW (LAW_ID, LAW_NAME)
      on delete restrict on update restrict;

alter table project.ARTICLE
   add constraint FK_ARTICLE_REFERENCE_REFERENC foreign key (REFERENCE_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

alter table project.LAW
   add constraint FK_LAW_REFERENCE_REFERENC foreign key (REFERENCE_ID)
      references common.REFERENCE (REFERENCE_ID)
      on delete restrict on update restrict;

