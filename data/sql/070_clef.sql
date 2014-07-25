/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de création :  24/07/2014 18:10:35                      */
/*==============================================================*/


alter table te_ip_ip
   add constraint pk_ip primary key (ip_id);

comment on constraint pk_ip ON te_ip_ip IS 'Clef primaire de l''adresse IP';

alter table te_ip_ip
   add constraint uk_ip_reseau unique (res_id, ip_id);

comment on constraint uk_ip_reseau ON te_ip_ip IS 'Contrainte d''unicité sur le réseau et l''adresse IP';

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

alter table tj_machine_ip
   add constraint pk_machine_ip primary key (ip_id, mac_id);

comment on constraint pk_machine_ip ON tj_machine_ip IS 'Clef de jointure machine et ip';

alter table te_ip_ip
   add constraint fk_ip_reseau foreign key (res_id)
      references te_reseau_res (res_id)
      on delete cascade on update cascade;

comment on constraint fk_ip_reseau ON te_ip_ip IS 'Référence d''intégrité de la table ip vers la table réseau';

alter table tj_machine_ip
   add constraint fk_machine_ip_ip foreign key (ip_id)
      references te_ip_ip (ip_id)
      on delete cascade on update cascade;

comment on constraint fk_machine_ip_ip ON tj_machine_ip IS 'Référence d''intégrité entre la table de jointure machine ip et ip';

alter table tj_machine_ip
   add constraint fk_machine_ip_machine foreign key (mac_id)
      references te_machine_mac (mac_id)
      on delete cascade on update cascade;

comment on constraint fk_machine_ip_machine ON tj_machine_ip IS 'référence d''intégrité de la table de jointure machine ip vers la table machine';

