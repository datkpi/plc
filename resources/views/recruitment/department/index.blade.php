@extends('recruitment.layouts.master')
@section('content')

    <div class="dx-viewport demo-container">
        <div id="diagram"> </div>
        <div id="popup"> </div>
    </div>
    <script src="{{ asset('js/devxtreme/department.js') }}"></script>

    <style>
        #diagram {
            height: 725px;
        }

        /* .dx-overlay-content .dx-popup-normal .dx-popup-draggable .dx-resizable {
                                display: none;
                            } */

        #diagram .template .template-name {
            font-weight: bold;
            text-decoration: underline;
        }

        #diagram .template .template-title {
            font-weight: bold;
            font-style: italic;
        }

        #diagram .template .template-button {
            cursor: pointer;
            font-size: 8pt;
            fill: navy;
        }

        #diagram .template .template-button:hover {
            text-decoration: underline;
        }

        .dx-popup-content {
            padding: 0;
        }

        .dx-popup-content .dx-fieldset.buttons {
            display: flex;
            justify-content: flex-end;
        }

        .dx-popup-content .dx-fieldset.buttons>* {
            margin-left: 8px;
        }
    </style>

    <script type="text/javascript">
        $('.delete_confirm').click(function(e) {
            if (!confirm('Bạn có muốn xoá bản ghi này?')) {
                e.preventDefault();
            }
        });
    </script>
@stop
