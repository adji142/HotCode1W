        $.contextMenu({
            selector: '#table1 tbody td.dataTables_empty',
            className: 'numberfilter',
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
                    options: {1: '<=', 2: '<', 3: '=', 4: '>', 5: '>='},
                    selected: 3
                },
                key: {
                    name: "Cancel",
                    callback: $.noop
                }
            },
            events: {
                show: function(opt) {
                    context_menu_number_state = 'show';
                    $(document).off('focusin.modal');
                    setTimeout(function(){
                        $('.context-menu-list.numberfilter input[name="context-menu-input-name"]').focus();
                    }, 10);
                },
                hide: function(opt) {
                    // this is the trigger element
                    var $this = this;
                    // export states to data store
                    var contextData = $.contextMenu.getInputValues(opt, $this.data());
                    console.log('number');
                    console.log(contextData);
                    context_menu_number_state = 'hide';
                    column_index = table.column(this).index();
                    if(column_index != undefined) {
                        last_index = column_index;
                    }else {
                        column_index = last_index;
                    }
                    custom_search[column_index].filter = filter_number[contextData.filter-1];
                    custom_search[column_index].text = contextData.name;
                    // table.columns(table.column(this).index()).search( contextData.name ).draw();
                    // this basically dumps the input commands' values to an object
                    // like {name: "foo", yesno: true, radio: "3", &hellip;}
                },
            }
        });

        $.contextMenu({
            selector: '#table1 tbody td.menufilter.numberfilter',
            className: 'numberfilter',
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
                    options: {1: '<=', 2: '<', 3: '=', 4: '>', 5: '>='},
                    selected: 3
                },
                key: {
                    name: "Cancel",
                    callback: $.noop
                }
            },
            events: {
                show: function(opt) {
                    context_menu_number_state = 'show';
                },
                hide: function(opt) {
                    // this is the trigger element
                    var $this = this;
                    // export states to data store
                    var contextData = $.contextMenu.getInputValues(opt, $this.data());
                    console.log('number');
                    console.log(contextData);
                    context_menu_number_state = 'hide';
                    column_index = table.column(this).index();
                    if(column_index != undefined) {
                        last_index = column_index;
                    }else {
                        column_index = last_index;
                    }
                    custom_search[column_index].filter = filter_number[contextData.filter-1];
                    custom_search[column_index].text = contextData.name;
                    // table.columns(table.column(this).index()).search( contextData.name ).draw();
                    // this basically dumps the input commands' values to an object
                    // like {name: "foo", yesno: true, radio: "3", &hellip;}
                },
            }
        });

        $.contextMenu({
            selector: '#table1 tbody td.menufilter.textfilter',
            className: 'textfilter',
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
                    context_menu_text_state = 'show';
                },
                hide: function(opt) {
                    // this is the trigger element
                    var $this = this;
                    // export states to data store
                    var contextData = $.contextMenu.getInputValues(opt, $this.data());
                    console.log('text');
                    console.log(contextData);
                    context_menu_text_state = 'hide';
                    column_index = table.column(this).index();
                    if(column_index != undefined) {
                        last_index = column_index;
                    }else {
                        column_index = last_index;
                    }
                    custom_search[column_index].filter = filter_text[contextData.filter-1];
                    custom_search[column_index].text = contextData.name;
                    // table.columns(table.column(this).index()).search( contextData.name ).draw();
                    // this basically dumps the input commands' values to an object
                    // like {name: "foo", yesno: true, radio: "3", &hellip;}
                },
            }
        });

        $(document.body).on("keydown", function(e){
            ele = document.activeElement;
            if(e.keyCode == 13){
                if(context_menu_number_state == 'show'){
                    $(".context-menu-list.numberfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }else if(context_menu_text_state == 'show'){
                    $(".context-menu-list.textfilter").contextMenu("hide");
                    table.ajax.reload(null, true);
                }
            }
        });
