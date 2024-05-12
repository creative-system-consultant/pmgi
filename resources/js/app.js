import './bootstrap';
import 'flowbite';
// import './dark-mode';
import ApexCharts from 'apexcharts'

//chart
const getBilMembayarChartOptions = () => {
	let mainChartColors = {}

	if (document.documentElement.classList.contains('dark')) {
		mainChartColors = {
			borderColor: '#374151',
			labelColor: '#9CA3AF',
			opacityFrom: 0,
			opacityTo: 0.15,
		};
	} else {
		mainChartColors = {
			borderColor: '#F3F4F6',
			labelColor: '#6B7280',
			opacityFrom: 0.45,
			opacityTo: 0,
		}
	}

	return {
		chart: {
			height: 420,
			type: 'area',
			fontFamily: 'Inter, sans-serif',
			foreColor: mainChartColors.labelColor,
			toolbar: {
				show: false
			}
		},
		fill: {
			type: 'gradient',
			gradient: {
				enabled: true,
				opacityFrom: mainChartColors.opacityFrom,
				opacityTo: mainChartColors.opacityTo
			}
		},
		dataLabels: {
			enabled: false
		},
		tooltip: {
			style: {
				fontSize: '14px',
				fontFamily: 'Inter, sans-serif',
			},
		},
		grid: {
			show: true,
			borderColor: mainChartColors.borderColor,
			strokeDashArray: 1,
			padding: {
				left: 35,
				bottom: 15
			}
		},
		series: [
			{
				name: 'Bil. Seliaan',
				data: [600, 559, 432, 561, 680, 554, 324],
				color: '#1A56DB'
			},
			{
				name: 'Bil. Membayar',
				data: [300, 279, 216, 280, 340, 290, 300],
				color: '#FDBA8C'
			}
		],
		markers: {
			size: 5,
			strokeColors: '#ffffff',
			hover: {
				size: undefined,
				sizeOffset: 3
			}
		},
		xaxis: {
			categories: ['Jul 18', 'Aug 18', 'Sep 18', 'Oct 18', 'Nov 18', 'Dec 18', 'Jan 19'],
			labels: {
				style: {
					colors: [mainChartColors.labelColor],
					fontSize: '14px',
					fontWeight: 500,
				},
			},
			axisBorder: {
				color: mainChartColors.borderColor,
			},
			axisTicks: {
				color: mainChartColors.borderColor,
			},
			crosshairs: {
				show: true,
				position: 'back',
				stroke: {
					color: mainChartColors.borderColor,
					width: 1,
					dashArray: 10,
				},
			},
		},
		yaxis: {
			labels: {
				style: {
					colors: [mainChartColors.labelColor],
					fontSize: '14px',
					fontWeight: 500,
				},
				formatter: function (value) {
					return value;
				}
			},
		},
		legend: {
			fontSize: '14px',
			fontWeight: 500,
			fontFamily: 'Inter, sans-serif',
			labels: {
				colors: [mainChartColors.labelColor]
			},
			itemMargin: {
				horizontal: 10
			}
		},
		responsive: [
			{
				breakpoint: 1024,
				options: {
					xaxis: {
						labels: {
							show: false
						}
					}
				}
			}
		]
	};
}

if (document.getElementById('bil-bayar-chart')) {
	const chart = new ApexCharts(document.getElementById('bil-bayar-chart'), getBilMembayarChartOptions());
	chart.render();

	// init again when toggling dark mode
	document.addEventListener('dark-mode', function () {
		chart.updateOptions(getBilMembayarChartOptions());
	});
}

if (document.getElementById('pk-dk-chart')) {
	const options = {
		colors: ['#1A56DB', '#FDBA8C'],
		series: [
			{
				name: 'Patut Kutip',
				color: '#1A56DB',
				data: [
					{ x: 'Jul 18', y: 170 },
					{ x: 'Aug 18', y: 180 },
					{ x: 'Sep 18', y: 164 },
					{ x: 'Oct 18', y: 145 },
					{ x: 'Nov 18', y: 194 },
					{ x: 'Dec 18', y: 170 },
					{ x: 'Jan 19', y: 155 },
				]
			},
			{
				name: 'Dapat Kutip',
				color: '#FDBA8C',
				data: [
					{ x: 'Jul 18', y: 120 },
					{ x: 'Aug 18', y: 294 },
					{ x: 'Sep 18', y: 167 },
					{ x: 'Oct 18', y: 179 },
					{ x: 'Nov 18', y: 245 },
					{ x: 'Dec 18', y: 182 },
					{ x: 'Jan 19', y: 143 }
				]
			},
		],
		chart: {
			type: 'bar',
			height: '420px',
			fontFamily: 'Inter, sans-serif',
			foreColor: '#4B5563',
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			bar: {
				columnWidth: '90%',
				borderRadius: 3
			}
		},
		tooltip: {
			shared : true,
			intersect: false,
			style: {
				fontSize: '14px',
				fontFamily: 'Inter, sans-serif'
			},
		},
		states: {
			hover: {
				filter: {
					type: 'darken',
					value: 1
				}
			}
		},
		stroke: {
			show: true,
			width: 5,
			colors: ['transparent']
		},
		grid: {
			show: false
		},
		dataLabels: {
			enabled: false,
		},
		legend: {
			show: true,
            fontSize: '14px',
            fontWeight: 500,
			fontFamily: 'Inter, sans-serif',
            horizontalAlign: 'center',
            itemMargin: {
				horizontal: 10,
			},
            markers: {
                radius: 10
            }
		},
		xaxis: {
			floating: false,
			labels: {
				show: true
			},
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
		},
		yaxis: {
			show: false
		},
		fill: {
			opacity: 1
		}
	};

	const chart = new ApexCharts(document.getElementById('pk-dk-chart'), options);
	chart.render();
}

const getLawatanChartOptions = () => {
    let mainChartColors = {};

    if (document.documentElement.classList.contains('dark')) {
        mainChartColors = {
            borderColor: '#374151',
            labelColor: '#9CA3AF',
            opacityFrom: 0,
            opacityTo: 0.15,
        };
    } else {
        mainChartColors = {
            borderColor: '#F3F4F6',
            labelColor: '#6B7280',
            opacityFrom: 0.45,
            opacityTo: 0,
        }
    }

    return {
        chart: {
            height: 420,
            type: 'line', // Default to line; specific series will override
            fontFamily: 'Inter, sans-serif',
            foreColor: mainChartColors.labelColor,
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: [0, 4] // Specify stroke width for column and line series respectively
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [0] // Enable data labels only for the line series (index 1)
        },
        plotOptions: {
			bar: {
				columnWidth: '90%',
				borderRadius: 3
			}
		},
        tooltip: {
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif',
            },
        },
        grid: {
            show: true,
            borderColor: mainChartColors.borderColor,
            strokeDashArray: 1,
            padding: {
                left: 0,
                bottom: 15
            }
        },
        series: [
            {
                name: 'Bil. Lawatan',
                type: 'column', // Column chart type
                data: [600, 559, 432, 561, 680, 554, 324], // Example data
                color: '#1A56DB'
            },
            {
                name: '% Lawat',
                type: 'line', // Line chart type
                data: [68.3, 65.9, 60.4, 72.2, 61.8, 65.2, 62.1], // Example data
                color: '#FDBA8C'
            }
        ],
        markers: {
            size: 5,
            strokeColors: '#ffffff',
            hover: {
                size: undefined,
                sizeOffset: 3
            }
        },
        xaxis: {
            categories: ['Jul 18', 'Aug 18', 'Sep 18', 'Oct 18', 'Nov 18', 'Dec 18', 'Jan 19'],
            labels: {
                style: {
                    colors: [mainChartColors.labelColor],
                    fontSize: '11px',
                    fontWeight: 500,
                },
            },
            axisBorder: {
                color: mainChartColors.borderColor,
            },
            axisTicks: {
                color: mainChartColors.borderColor,
            },
            crosshairs: {
                show: true,
                position: 'back',
                stroke: {
                    color: mainChartColors.borderColor,
                    width: 1,
                    dashArray: 10,
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: [mainChartColors.labelColor],
                    fontSize: '14px',
                    fontWeight: 500,
                },
                formatter: function (value) {
                    return value;
                }
            },
        },
        legend: {
            fontSize: '14px',
            fontWeight: 500,
            fontFamily: 'Inter, sans-serif',
            labels: {
                colors: [mainChartColors.labelColor]
            },
            itemMargin: {
                horizontal: 10
            }
        },
        responsive: [
            {
                breakpoint: 1024,
                options: {
                    xaxis: {
                        labels: {
                            show: false
                        }
                    }
                }
            }
        ]
    };
};

if (document.getElementById('lawatan-chart')) {
    const chart = new ApexCharts(document.getElementById('lawatan-chart'), getLawatanChartOptions());
    chart.render();

    // Re-initialize when toggling dark mode
    document.addEventListener('dark-mode', function () {
        chart.updateOptions(getLawatanChartOptions());
    });
}