<?php
    /**
     * @var \App\View\AppView $this
     * @var int $members Total number of members registered
     * @var int $stations Total number of voting stations
     * @var int $branches Total number of branches
     * @var int $max Maximum value for bar chart
     * @var int $volunteers System admin statistics
     * @var int $supporters System admin statistics
     * @var int $volunteers_review System admin statistics
     * @var array $monthly_referred Total registered by volunteers or through referrals
     * @var array $monthly_self Total self registrations
     * @var array $membersByProvinceColours Colours for the provinces circle
     * @var array $membersByProvince self explanatory
     */

    $this->assign('title', __('Dashboard'));
?>

<div class="row">
    <!-- ICON BG-->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Add-User"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Members</p>
                    <p class="text-primary text-24 line-height-1 mb-2"><?= $members ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Financial"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Stations</p>
                    <p class="text-primary text-24 line-height-1 mb-2"><?= $stations ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Branches</p>
                    <p class="text-primary text-24 line-height-1 mb-2"><?= $branches ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Money-2"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Call to </p>
                    <p class="text-primary text-24 line-height-1 mb-2">Action</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">Monthly Member Registrations</div>
                <div id="asaChartBar" style="height: 250px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">Members by Provinces</div>
                <div id="asaChartPie" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</div>

<?php if (in_array($this->Identity->get('role_id'), [1, 2, 3])): ?>
<div class="row">
    <!-- ICON BG-->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Add-User"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Volunteers</p>
                    <p class="text-primary text-24 line-height-1 mb-2"><a href="/system/admin/users/?status=<?= MEMBER_VOLUNTEER ?>"><?= $volunteers ?></a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Financial"></i>
                <div class="content">
                    <p class="text-muted mt-2 mb-0">Supporters</p>
                    <p class="text-primary text-24 line-height-1 mb-2"><a href="/system/admin/users/?status=<?= MEMBER_SUPPORTER ?>"><?= $supporters ?></a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                <div class="content" style="max-width: 100px;">
                    <p class="text-muted mt-2 mb-0"><small>Volunteer Review</small></p>
                    <p class="text-primary text-24 line-height-1 mb-2"><a href="/system/admin/users/?status=<?= MEMBER_VOLUNTEER_REVIEW ?>"><?= $volunteers_review ?></a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
            <div class="card-body text-center"><i class="i-Money-2"></i>
                <div class="content" style="max-width: 100px;">
                    <p class="text-muted mt-2 mb-0"><small>REMOVED</small></p>
                    <p class="text-primary text-24 line-height-1 mb-2">-</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $this->start('footer_scripts'); ?>
<script src="/js/plugins/echarts.min.js"></script>
<script src="/js/scripts/echart.options.min.js"></script>
<script>
    "use strict";

    function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

    function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(source, true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(source).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

    function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

    $(document).ready(function () {
        // Chart in Dashboard version 1
        var echartElemBar = document.getElementById('asaChartBar');

        if (echartElemBar) {
            var echartBar = echarts.init(echartElemBar);
            echartBar.setOption({
                legend: {
                    borderRadius: 0,
                    orient: 'horizontal',
                    x: 'right',
                    data: ['Self Registered', 'Volunteer']
                },
                grid: {
                    left: '8px',
                    right: '8px',
                    bottom: '0',
                    containLabel: true
                },
                tooltip: {
                    show: true,
                    backgroundColor: 'rgba(0, 0, 0, .8)'
                },
                xAxis: [{
                    type: 'category',
                    data: [<?php echo '"', implode('", "', array_keys($monthly_self)) . '"'; ?>],
                    axisTick: {
                        alignWithLabel: true
                    },
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: true
                    }
                }],
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    },
                    min: 0,
                    max: <?= $max ?>,
                    interval: <?= (0 !== $max) ? $max / 5 : 0 ?>,
                    axisLine: {
                        show: false
                    },
                    splitLine: {
                        show: true,
                        interval: 'auto'
                    }
                }],
                series: [
                {
                    name: 'Self Registered',
                    data: [<?php echo implode(',', $monthly_self); ?>],
                    label: {
                        show: false,
                        color: '#05b616'
                    },
                    type: 'bar',
                    barGap: 0,
                    color: '#bfebc2',
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                },
                {
                    name: 'Volunteer',
                    data: [<?php echo implode(',', $monthly_referred); ?>],
                    label: {
                        show: false,
                        color: '#05b616'
                    },
                    type: 'bar',
                    color: '#05b616',
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                }]
            });
            $(window).on('resize', function () {
                setTimeout(function () {
                    echartBar.resize();
                }, 500);
            });
        } // Chart in Dashboard version 1


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
