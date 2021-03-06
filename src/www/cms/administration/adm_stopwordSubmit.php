<?php
require '../../include/inc.bo_init.php';
require_once CLASS_DIR . 'class.db_page.php';
CMS::checkAccess(new Module('MOD_CORE'), array('PRO_ROOT'));

if (isset($_POST['Insert']) || isset($_POST['Update'])) {
    $_POST['STP_LIBELLE'] = preg_replace('/[^a-z]/', '', reduceToISO646(mb_strtolower($_POST['STP_LIBELLE'])));

    if (isset($_POST['Insert'])) {
        setMsg(gettext('INSERT_OK'));
    } else {
        $sql = "delete from STOPWORD_LANGUE where STP_LIBELLE=" . $dbh->quote($_POST['idtf']) . " and LNG_CODE=" . $dbh->quote($_POST['LNG_CODE']);
        $dbh->exec($sql);
        setMsg(gettext('UPDATE_OK'));
    }

    $stmt = $dbh->prepare("replace into STOPWORD_LANGUE (
        STP_LIBELLE,
        LNG_CODE
        ) values (
        :STP_LIBELLE,
        :LNG_CODE
        )");
    $stmt->bindValue(':STP_LIBELLE', $_POST['STP_LIBELLE'], PDO::PARAM_STR);
    $stmt->bindValue(':LNG_CODE', $_POST['LNG_CODE'], PDO::PARAM_STR);
    $stmt->execute();
} elseif (isset($_GET['Delete'])) {
    $sql = "delete from STOPWORD_LANGUE where STP_LIBELLE=" . $dbh->quote($_GET['Delete']) . " and LNG_CODE=" . $dbh->quote($_GET['LNG_CODE']);
    $dbh->exec($sql);
    setMsg(gettext('DELETE_OK'));
}

// Purge du cache de l'ensemble des sites
Page::clearAllCache();

header('Location:' . SERVER_ROOT . 'cms/administration/adm_stopwordListe.php?LNG_CODE=' . $_REQUEST['LNG_CODE']);
exit ();
