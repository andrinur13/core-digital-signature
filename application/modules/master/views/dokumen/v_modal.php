<table class="table table-striped table-bordered" id="datatables_sm" width="100%">
    <thead>
        <tr role="row" class="heading">
            <th width="2%">
                Pilih
            </th>
            <th width="2%">Kode Pejabat</th>
            <th width="10%">Nama Pejabat</th>
            <th width="3%">Jabatan</th>
        </tr>
    </thead>
</table>
<script>
    $(function() {
        var ajaxParams = {};
        var setAjaxParams = function(name, value) {
            ajaxParams[name] = value;
        };  
     var dt = $('#datatables_sm').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[5,20, 35, 50, -1], [5,20, 35, 50, "All"]],
            "pageLength": '5',
            "ajax": {
                "url": "<?php echo site_url($module . '/ajax/datatables_modal'); ?>",
                "type": "POST",
                "data": function(d) {
                //d.order_asesiNama = "sutri";
                $.each(ajaxParams, function(key, value) {
                    d[key] = value;
                });
                }
            },
            'drawCallback': function(settings) {
                $('[data-provide="tooltip"]').tooltip();
            },
            "createdRow": function (row, data, index) {
                $(row.cells[1]).attr('id', 'kode-pejabat');
                $(row.cells[2]).attr('id', 'nama-pejabat');
                $(row.cells[3]).attr('id', 'jabatan-pejabat');
            },
            //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
            'language': {
                'search': 'Cari',
                'searchPlaceholder': 'Search Data',
                'lengthMenu': "Tampil _MENU_",
                'info': "_START_ - _END_ dari _TOTAL_",
                "paginate": {
                "previous": "Prev",
                "next": "Next",
                "last": "Last",
                "first": "First",
                "page": "Page",
                "pageOf": "of"
                }
            },
            'order': [
                [1, 'asc']
            ],
            'columnDefs': [
                //  {"visible": false, "targets":[1]},
                {"orderable": false, "searchable": false, "targets": [0,1,2,3]},
                {"className":"text-center", "targets": [0,1,2,3]}
            ]

        });

        $('#datatables_sm').on('click', '#btnPilih', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id'),
                kode_pejabat = $(this).parents('tr').find('#kode-pejabat').text(),
                nama_pejabat = $(this).parents('tr').find('#nama-pejabat').text(),
                jabatan_pejabat = $(this).parents('tr').find('#jabatan-pejabat').text();

        let $tbody = $('#tablePejabat').find('tbody');
        var $empty_list = $tbody.find('#empty-list').length;
        if ($empty_list > 0) {
            $tbody.find('#empty-list').remove();
        }

        let $tr = $('#tempPejabatList').clone(true).attr('id', 'pejabat').removeAttr('style');

        var list = $tbody.find('tr'), counter = (list.length - 1);

        $tr.find('[scope="row"]').text(counter+1);
        $tr.find('.kode_pejabat').text(kode_pejabat);
        $tr.find('.nama_pejabat').text(nama_pejabat);
        $tr.find('.jabatan_pejabat').text(jabatan_pejabat);

        $tr.find('#id-pejabat').attr('name', 'pejabat_id[]').val(id);

        $tr.appendTo($tbody);
        $('.modal').modal('hide');

        });

    });
</script>