select idC,tbl_cont.idtipo,tbl_cont.idcont_tab,tbl_cont.nome_tabella from tbl_rel_pag_cont, 
(select tbl_contenuto.id, tbl_contenuto.idtipo,tbl_contenuto.idcont_tab, tbl_tipocontenuto.descrizione,nome_tabella from tbl_contenuto, tbl_tipocontenuto where tbl_contenuto.idtipo=tbl_tipocontenuto.id) as tbl_cont 
where tbl_cont.id=1 order by tbl_cont.idtipo, tbl_cont.idcont_tab;