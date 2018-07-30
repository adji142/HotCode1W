var barang_column_index  = 0;
var barang_text_state    = 'hide';
var barang_empty_state   = 'hide';
var barang_filter_text   = ['=','!='];
var barang_custom_search = [[],[]];
// var barang_custom_search = [
//     { text : '', filter : '=' },
//     { text : '', filter : '=' },
// ];

$.contextMenu({
    selector: '#tbodyBarang td.menufilter.textfilter',
    className: 'barangtextfilter',
    items: {
        name: {
            name: "Text",
            type: 'text',
            value: "",
            events: {
                keyup: function(e) {
                    // add some fancy key handling here?
                    // window.console && console.log('key: '+ e.keyCode);
                }
            }
        },
        // tipe: {
        //     name: "Tipe",
        //     type: 'select',
        //     options: {1: 'Find', 2: 'Filter'},
        //     selected: 1
        // },
        filter: {
            name: "Filter",
            type: 'select',
            options: {1: '=', 2: '!='},
            selected: 1
        },
        key: {
            name: "Cancel",
            callback: $.noop
        }
    },
    events: {
        show: function(opt) {
            barang_text_state = 'show';
            $(document).off('focusin.modal');
            setTimeout(function(){
                $('.context-menu-list.barangtextfilter input[name="context-menu-input-name"]').focus();
            }, 10);
        },
        hide: function(opt) {
            // this is the trigger element
            var $this = this;
            // export states to data store
            var contextData = $.contextMenu.getInputValues(opt, $this.data());

            barang_text_state   = 'hide';
            barang_column_index = $this.index();
            if(contextData.name == "") {
                barang_custom_search[barang_column_index] = [];
            }else{
                var count = $.map(barang_custom_search[barang_column_index], function(n, i) { return i; }).length;

                if(count >= 4){
                    barang_custom_search[barang_column_index] = [];
                }

                barang_custom_search[barang_column_index].push({filter : barang_filter_text[contextData.filter-1] , text : contextData.name});
            }
        },
    }
});

$.contextMenu({
    selector: '#tbodyBarang td.dataTables_empty',
    className: 'barangemptyfilter',
    items: {
        name: {
            name: "Text",
            type: 'text',
            value: "",
            events: {
                keyup: function(e) {
                    // add some fancy key handling here?
                    // window.console && console.log('key: '+ e.keyCode);
                }
            }
        },
        // tipe: {
        //     name: "Tipe",
        //     type: 'select',
        //     options: {1: 'Find', 2: 'Filter'},
        //     selected: 1
        // },
        filter: {
            name: "Filter",
            type: 'select',
            options: {1: '=', 2: '!='},
            selected: 1
        },
        key: {
            name: "Cancel",
            callback: $.noop
        }
    },
    events: {
        show: function(opt) {
            barang_empty_state = 'show';
            $(document).off('focusin.modal');
            setTimeout(function(){
                $('.context-menu-list.barangemptyfilter input[name="context-menu-input-name"]').focus();
            }, 10);
        },
        hide: function(opt) {
            // this is the trigger element
            var $this = this;
            // export states to data store
            var contextData = $.contextMenu.getInputValues(opt, $this.data());

            barang_empty_state   = 'hide';
            barang_column_index = $this.index();

            // Clear First
            barang_custom_search = [[],[]];

            if(contextData.name == "") {
                barang_custom_search[barang_column_index] = [];
            }else{
                var count = $.map(barang_custom_search[barang_column_index], function(n, i) { return i; }).length;

                if(count >= 4){
                    barang_custom_search[barang_column_index] = [];
                }

                barang_custom_search[barang_column_index].push({filter : barang_filter_text[contextData.filter-1] , text : contextData.name});
            }
        },
    }
});

$(document.body).on("keydown", function(e){
    ele = document.activeElement;
    if(e.keyCode == 13){
        if(barang_text_state == 'show'){
            $(".context-menu-list.barangtextfilter").contextMenu("hide");
            search_barang($('#txtQueryBarang').val());
        }
        if(barang_empty_state == 'show'){
            $(".context-menu-list.barangemptyfilter").contextMenu("hide");
            search_barang($('#txtQueryBarang').val());
        }
    }
});