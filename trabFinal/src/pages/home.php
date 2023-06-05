<?php include($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/components/header.php'); ?>

<body>
    <?php echo $_SERVER['DOCUMENT_ROOT']; ?>
    <?php var_dump(unserialize($_SESSION['user']));; ?>
</body>

<?php include($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/components/footer.php'); ?>