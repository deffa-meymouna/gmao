/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de création :  04/09/2014 15:14:26                      */
/*==============================================================*/


alter table friends
   add constraint friends_pkey primary key (user_id, friend_id);

comment on constraint friends_pkey ON friends IS 'Clefs primaire de la table amie';

alter table language
   add constraint language_pkey primary key (id);

comment on constraint language_pkey ON language IS 'clef primaire et étrangère de la table langage';

alter table privilege
   add constraint privilege_pkey primary key (id);

comment on constraint privilege_pkey ON privilege IS 'Clef primaire de la table privilège';

alter table question
   add constraint question_pkey primary key (id);

comment on constraint question_pkey ON question IS 'Clef primaire de la table question';

/*==============================================================*/
/* Index : uk_question                                          */
/*==============================================================*/
create unique index uk_question on question using BTREE (
question
)
;

comment on index uk_question  IS 'Contrainte d''unicité sur la question';

alter table resource
   add constraint resource_pkey primary key (id);

comment on constraint resource_pkey ON resource IS 'clef primaire de la ressource';

alter table role
   add constraint role_pkey primary key (id);

comment on constraint role_pkey ON role IS 'Clef primaire du rôle';

alter table role
   add constraint uk_role_name unique (name);

comment on constraint uk_role_name ON role IS 'Contrainte d''unicité sur le nom du rôle';

alter table roles_parents
   add constraint roles_parents_pkey primary key (role_id, parent_id);

comment on constraint roles_parents_pkey ON roles_parents IS 'Clef primaire de la table role_parents';

alter table state
   add constraint state_pkey primary key (id);

comment on constraint state_pkey ON state IS 'Clef primaire de la table état';

alter table state
   add constraint uk_state_name unique (state);

comment on constraint uk_state_name ON state IS 'Contrainte d''unicité sur le nom de l''état';

alter table te_ip_ip
   add constraint pk_ip primary key (ip_id);

comment on constraint pk_ip ON te_ip_ip IS 'Clef primaire de l''adresse IP';

alter table te_ip_ip
   add constraint AK_UK_IP_ADDESSE_TE_IP_IP unique (res_id, ip_adresse);

comment on constraint AK_UK_IP_ADDESSE_TE_IP_IP ON te_ip_ip IS '';

/*==============================================================*/
/* Index : i1_ip                                                */
/*==============================================================*/
create  index i1_ip on te_ip_ip (
ip_adresse
)
tablespace tbl_iptrevise_index;

comment on index i1_ip  IS 'Index sur l''adresse IP';

alter table te_machine_mac
   add constraint pk_machine primary key (mac_id);

comment on constraint pk_machine ON te_machine_mac IS 'Clef primaire de la machine';

/*==============================================================*/
/* Index : i1_mac                                               */
/*==============================================================*/
create  index i1_mac on te_machine_mac (
mac_lib
)
tablespace tbl_iptrevise_index;

comment on index i1_mac  IS 'Index sur le libellé de la machine';

/*==============================================================*/
/* Index : i2_mac                                               */
/*==============================================================*/
create  index i2_mac on te_machine_mac (
mac_type
)
tablespace tbl_iptrevise_index;

comment on index i2_mac  IS 'Index sur le type de la machine';

alter table te_reseau_res
   add constraint pk_res primary key (res_id);

comment on constraint pk_res ON te_reseau_res IS 'Clef primaire de l''entité réseau';

/*==============================================================*/
/* Index : i1_res                                               */
/*==============================================================*/
create  index i1_res on te_reseau_res (
res_ip,
res_masque
)
tablespace tbl_iptrevise_index;

comment on index i1_res  IS 'Index sur le couple ip masque du réseau';

/*==============================================================*/
/* Index : i2_res                                               */
/*==============================================================*/
create  index i2_res on te_reseau_res (
res_passerelle
)
tablespace tbl_iptrevise_index;

comment on index i2_res  IS 'Index sur la passerelle déclarée';

/*==============================================================*/
/* Index : i3_res                                               */
/*==============================================================*/
create  index i3_res on te_reseau_res (
res_lib
)
tablespace tbl_iptrevise_index;

comment on index i3_res  IS 'Index sur le libellé du réseau';

alter table "user"
   add constraint user_pkey primary key (id);

comment on constraint user_pkey ON "user" IS 'Clef primaire de la table user';

alter table "user"
   add constraint uk_user_username unique (username);

comment on constraint uk_user_username ON "user" IS 'Contrainte dunicité sur l''username';

alter table "user"
   add constraint uk_user_email unique (email);

comment on constraint uk_user_email ON "user" IS 'Contrainte d''unicité sur l''email de l''utilisateur';

/*==============================================================*/
/* Index : usr_i1                                               */
/*==============================================================*/
create  index usr_i1 on "user" using BTREE (
username,
first_name,
last_name,
email
)
;

comment on index usr_i1  IS 'Index de recherche sur le nom';

alter table friends
   add constraint fk_friends_user foreign key (user_id)
      references "user" (id)
      on delete cascade on update cascade;

comment on constraint fk_friends_user ON friends IS 'Clef étrangère définissant l''ami d''un utilisateur';

alter table friends
   add constraint fk_privilege_role foreign key (friend_id)
      references "user" (id)
      on delete cascade on update cascade;

comment on constraint fk_privilege_role ON friends IS 'Clef étrangère définissant l''ami d''un utilisateur';

alter table privilege
   add constraint fk_privilege_role foreign key (role_id)
      references role (id)
      on delete cascade on update cascade;

comment on constraint fk_privilege_role ON privilege IS 'Clef étrangère définissant le rôle d''un privilège';

alter table roles_parents
   add constraint fk_c70e6b91727aca70 foreign key (parent_id)
      references role (id)
      on delete cascade on update cascade;

comment on constraint fk_c70e6b91727aca70 ON roles_parents IS 'Clef étrangère définissant le role parent d''un rôle';

alter table roles_parents
   add constraint fk_roles_parents_role2 foreign key (role_id)
      references role (id)
      on delete cascade on update cascade;

comment on constraint fk_roles_parents_role2 ON roles_parents IS 'Clef étrangère définissant les parents d''un rôle';

alter table te_ip_ip
   add constraint fk_ip_mac foreign key (mac_id)
      references te_machine_mac (mac_id)
      on delete set null on update restrict;

comment on constraint fk_ip_mac ON te_ip_ip IS '';

alter table te_ip_ip
   add constraint fk_ip_reseau foreign key (res_id)
      references te_reseau_res (res_id)
      on delete cascade on update cascade;

comment on constraint fk_ip_reseau ON te_ip_ip IS 'Référence d''intégrité de la table ip vers la table réseau';

alter table te_ip_ip
   add constraint FK_TE_IP_IP_FK_IP_USE_USER foreign key (usr_id)
      references "user" (id)
      on delete restrict on update restrict;

comment on constraint FK_TE_IP_IP_FK_IP_USE_USER ON te_ip_ip IS 'Clef étrangère du créateur de la requête';

alter table te_machine_mac
   add constraint fk_mac_user foreign key (usr_id)
      references "user" (id)
      on delete restrict on update restrict;

comment on constraint fk_mac_user ON te_machine_mac IS 'Clef étrangère du créateur de la machine';

alter table te_reseau_res
   add constraint fk_res_user foreign key (usr_id)
      references "user" (id)
      on delete restrict on update restrict;

comment on constraint fk_res_user ON te_reseau_res IS 'Clef étrangère du créateur du réseau';

alter table "user"
   add constraint fk_user_language foreign key (language_id)
      references language (id)
      on delete restrict on update restrict;

comment on constraint fk_user_language ON "user" IS 'Clef étrangère définissant la langue de l''utilisateur';

alter table "user"
   add constraint fk_user_question foreign key (question_id)
      references question (id)
      on delete restrict on update restrict;

comment on constraint fk_user_question ON "user" IS 'Clef étrangère définissant la question d''un utilisateur pour récupérer son mot de passe';

alter table "user"
   add constraint fk_user_role foreign key (role_id)
      references role (id)
      on delete restrict on update restrict;

comment on constraint fk_user_role ON "user" IS 'Clef étrangère définissant le rôle de l''utilisateur';

alter table "user"
   add constraint fk_user_state foreign key (state_id)
      references state (id)
      on delete restrict on update restrict;

comment on constraint fk_user_state ON "user" IS 'Clef étrangère définissant le statut d''un utilisateur';

