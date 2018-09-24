import ElementUI, { DatePicker } from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale })

if (document.getElementById('layout_date_selector')) {
    new Vue({
        el: '#layout_date_selector',
        components: {
            DatePicker,
        },
        data: {
            selected_range: '',
            picker: {
                selection: undefined,
                options: {
                    firstDayOfWeek: 1, // Using 1 to start from Monday (options: 1 to 7)
                    shortcuts: [
                        {
                            text: 'Yesterday',
                            onClick(picker) {
                                const start = new Date();

                                start.setTime(start.getTime() - 864e5); // remove one day

                                picker.$emit('pick', [start, start]);
                            },
                        },
                        {
                            text: 'This week',
                            onClick(picker) {
                                const today = new Date(),
                                    start = new Date(),
                                    end = new Date();

                                start.setDate(today.getDate() - today.getDay() + 1); // monday
                                end.setDate(start.getDate() + 6); // sunday

                                picker.$emit('pick', [start, end]);
                            },
                        },
                        {
                            text: 'Last week',
                            onClick(picker) {
                                const today = new Date(),
                                    start = new Date(),
                                    end = new Date();

                                start.setDate((today.getDate() - today.getDay() + 1) - 7); // monday
                                end.setDate(start.getDate() + 6); // sunday

                                picker.$emit('pick', [start, end]);
                            },
                        },
                        {
                            text: 'Last month',
                            onClick(picker) {
                                const year = (new Date()).getFullYear(),
                                    month = (new Date()).getMonth(), // Note: month starts from 0
                                    lastDay = new Date(year, month, 0).getDate();

                                picker.$emit('pick', [
                                    `${year}-${month}-01`,
                                    `${year}-${month}-${lastDay}`
                                ]);
                            }
                        },
                        {
                            text: 'This year',
                            onClick(picker) {
                                const year = (new Date()).getFullYear();
                                picker.$emit('pick', [
                                    `${year}-01-01`,
                                    `${year}-12-31`
                                ]);
                            }
                        }
                    ]
                }
            }
        },
        created: function () {
            let _this = this;
            $(document).on('update-live-mode', function (e, is_live) {
                if (is_live)
                    _this.picker.selection = '';
            });
        },
        methods: {
            picked: function (e) {
                console.log(this.picker.selection);

                $(document).trigger('update-layout-date-range', [this.picker.selection]);
            }
        }
    });
}
