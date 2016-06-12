<script type="text/javascript">
    $(document).ready(function() {
        var editButton = $("button[name='edit-button']");
        var essentialButton = $("button[name='essential-button']");
        var wikiButton = $("button[name='wiki-button']");
        var deleteButton = $("button[name='delete-button']");

        editButton.click(function() {
            window.location.href = "/article/" + $(this).val() + "/edit";
        });
        essentialButton.click(function() {
            var active_button = $(this);
            $.ajax("/article/" + active_button.val() + "/set_essential", {
                type: 'post',
                data: {},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    if(response == 1) {
                        active_button.attr('id', 'article-show-essential-button-on');
                    }
                    if(response == 0) {
                        active_button.attr('id', '');
                    }
                }
            });
        });
        wikiButton.click(function() {
            var active_button = $(this);
            $.ajax("/article/" + active_button.val() + "/set_wiki", {
                type: 'post',
                data: {},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    if(response == 1) {
                        active_button.attr('id', 'article-show-wiki-button-on');
                    }
                    if(response == 0) {
                        active_button.attr('id', '');
                    }
                }
            });
        });
        deleteButton.click(function() {
            var active_button = $(this);
            swal({
                title: "您希望删除这篇文章吗？",
                text: "您确认删除这篇文章后，文章将不再会被读者看到，并进入垃圾箱，直到您将它恢复。",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function() {
                $.ajax("/article/" + active_button.val(), {
                    type: 'post',
                    data: {_method:"delete"},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if(response == 1) {
                            swal({
                                title: "删除成功",
                                text: "这篇文章已删除，并进入垃圾箱。",
                                type: "success",
                                confirmButtonText: "返回首页",
                                closeOnConfirm: false
                            }, function() {
                                // 需要修改：如果是/user/{name}/article下则刷新当前页即可
                                window.location.href = "{{ url('/') }}";
                            });
                        }
                        if(response == 0) {
                            swal("删除失败!", "您的删除操作并未成功执行，请再试一次。", "error");
                        }
                    }
                });

            });

        });
    });
</script>