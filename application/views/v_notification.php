<script type="text/javascript">
$(function() {
    <?php if ($this->session->flashdata('msg')) {
        $msg = $this->session->flashdata('msg');
    ?>
    Swal.fire({
        title: "<?= $msg['title'];?>",
        html: '<?=  $msg['message'];?>',
        icon: "<?=  $msg['status'];?>"
    });

    <?php } ?>
});
</script>