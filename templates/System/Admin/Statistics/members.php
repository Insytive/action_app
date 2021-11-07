<?php
    /**
     * @var \App\View\AppView $this
     * @var int $members Total number of members registered
     * @var int $stations Total number of voting stations
     *
     * @var array $membersByProvinceColours Colours for the provinces circle
     * @var array $membersByProvince self explanatory
     */

    $this->assign('title', __('Statistics'));
?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">Members by Province</div>
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Province</th>
                                <th scope="col" class="text-right">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($membersByProvince as $province): ?>
                            <tr>
                                <td><?= $province['name']; ?></td>
                                <td class="font-weight-bold text-right"><?= number_format($province['value']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-8">
                        <div id="asaChartPie" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $this->start('footer_scripts'); ?>
<script src="/js/plugins/echarts.min.js"></script>
<script src="/js/scripts/echart.options.min.js"></script>
<script>
    "use strict";

    $(document).ready(function () {
        var echartElemPie = document.getElementById('asaChartPie');

        if (echartElemPie) {
            var echartPie = echarts.init(echartElemPie);
            echartPie.setOption({
                color: [<?php echo '"', implode('", "', $membersByProvinceColours) . '"'; ?>],
                tooltip: {
                    show: true,
                    backgroundColor: 'rgba(0, 0, 0, .8)'
                },
                series: [{
                    name: 'Members by Provinces',
                    type: 'pie',
                    radius: '60%',
                    center: ['50%', '50%'],
                    data: <?php echo json_encode($membersByProvince) ?>,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            });
            $(window).on('resize', function () {
                setTimeout(function () {
                    echartPie.resize();
                }, 500);
            });
        } // Chart in Dashboard version 1

    });
</script>
<?php $this->end(); ?>
