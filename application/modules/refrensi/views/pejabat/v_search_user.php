<div class="divider color-primary">Informasi Akun Pejabat &nbsp; | &nbsp;<a href="#" onclick="loadButton('buat-form')" class="text-info"> Buat Akun</a></small></div>
<input type="hidden" name="user_pejabat" value="update_user_pejabat">
<div id="cari-akun">
    <div class="form-row autocomplete">
        <div class="form-group col-md-12">
            <input name="userName" id="searchInput" value="<?= isset($user) ? $user['UserName'] : ''; ?>" class="form-control" type="text" placeholder="Silahkan Cari Username..." autocomplete="off">
        </div>  
        <div class="autocomplete-results" id="autocomplete-results"></div>
    </div>

    <?php
        if(isset($user)){
    ?>
<div class="detail-akun-cek">
        <div class="divider color-primary">Detail Akun</div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Nama Lengkap</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="">: <?= $user['UserRealName']?></p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Username</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="">: <?= $user['UserName']?></p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Group</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="">: Pejabat</p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <?php
        }
    ?>
    <div class="detail-akun" style="display:none;">
        <div class="divider color-primary">Detail Akun</div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Nama Lengkap</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="nama">: </p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Username</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="username">: </p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label" for="input-2">Group</label>
            <div class="col-8 col-lg-10">
                <p class="col-form-label" id="pejabat">: Pejabat</p>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    function showMinLengthMessage() {
        if ($("#searchInput").val().length < 2) {
            $(".ui-autocomplete").html("<li class='ui-menu-item' role='menuitem'>Minimal 2 karakter</li>");
        }
    }

    $("#searchInput").on("focus", function() {
        showMinLengthMessage();
        $(".ui-autocomplete").html("<li class='ui-menu-item' role='menuitem'>Minimal 2 karakter</li>");
    });

    $("#searchInput").autocomplete({
        source: function(request, response){
            $.ajax({
                url: "<?php echo site_url($module.'/ajax/search_user'); ?>",
                dataType: "json",
                type: 'POST',
                data: {
                    query: request.term
                },
                success: function(data){
                    response($.map(data, function(item){
                        var searchTerm = request.term ? new RegExp(request.term, "gi") : null;
                        var highlightedText = item.username;

                        if (searchTerm) {
                            highlightedText = item.username.replace(searchTerm, function(matched){
                                return "<span class='text-warning'>" + matched + "</span>";
                            });
                        }

                        return {
                            label: highlightedText,
                            value: item.username,
                            id:item.id,
                            real_name:item.real_name,
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            var UserName = ui.item.value;
            var UserRealName = ui.item.real_name;
            $(".detail-akun").show();
            $(".detail-akun-cek").hide();

            $("#username").text(": " + UserName);
            $("#nama").text(": " + UserRealName);
        },
        open: function( event, ui ) {
            $(".ui-autocomplete").css('z-index', '9999');
        },
        close: function( event, ui ) {
            $(".ui-autocomplete").css('z-index', '');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li>")
            .append(item.label)
            .appendTo(ul);
    };

    $("#searchInput").on("autocompleteclose", function(event, ui) {
        showMinLengthMessage();
    });
});
</script>
