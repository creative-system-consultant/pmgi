import ApexCharts from 'apexcharts';

const getBilMembayarChartOptions = (data) => {
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

	const categories = data.map(item => item.report_date);
	const bilSeliaanData = data.map(item => item.bil_selia);
	const bilMembayarData = data.map(item => item.bil_dapat_kutip);

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
				data: bilSeliaanData,
				color: '#1A56DB'
			},
			{
				name: 'Bil. Membayar',
				data: bilMembayarData,
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
			categories: categories,
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

const getPkDkChartOptions = (data) => {
	const categories = data.map(item => item.report_date);
	const pkData = data.map(item => item.rm_patut_kutip);
	const dkData = data.map(item => item.rm_dapat_kutip);

	return {
		colors: ['#1A56DB', '#FDBA8C'],
		series: [
			{
				name: 'Patut Kutip',
				color: '#1A56DB',
				data: pkData
			},
			{
				name: 'Dapat Kutip',
				color: '#FDBA8C',
				data: dkData
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
			shared: true,
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
			categories: categories,
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
};

const getLawatanChartOptions = (data) => {
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

	const categories = data.map(item => item.report_date);
	const bilLawatanData = data.map(item => item.bil_lawat);
	const percentLawatanData = data.map(item => item.bil_lawat_pts);

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
			shared: false,
			intersect: true,
			custom: function({ series, seriesIndex, dataPointIndex, w }) {
				const bilLawatan = series[seriesIndex][dataPointIndex];
				const percentLawat = percentLawatanData[dataPointIndex];

				return `
					<div class="px-3 py-2 text-sm">
						<div><strong>Bulan:</strong> ${w.globals.categoryLabels[dataPointIndex]}</div>
						<div><strong>Bil. Lawatan:</strong> ${bilLawatan}</div>
						<div><strong>% Lawat:</strong> ${percentLawat}%</div>
					</div>`;
			}
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
				data: bilLawatanData, // Example data
				color: '#1A56DB'
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
			categories: categories,
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

const getCombinedChartOptions = (data) => {
  const categories = data.map(item => item.report_date);

  const bilSeliaanData = data.map(item => item.bil_selia);
  const bilMembayarData = data.map(item => item.bil_dapat_kutip);
  const bilLawatanData = data.map(item => item.bil_lawat);
  const percentLawatanData = data.map(item => item.bil_lawat_pts); // tooltip sahaja

  return {
    chart: {
      height: 420,
      type: 'line',
      stacked: false,
      fontFamily: 'Inter, sans-serif',
      toolbar: { show: false }
    },

    // 🔑 ORDER PENTING: BAR LAST supaya tak ditindih
    series: [
      {
        name: 'Bil. Seliaan',
        type: 'line',
        data: bilSeliaanData
      },
      {
        name: 'Bil. Membayar',
        type: 'line',
        data: bilMembayarData
      },
      {
        name: 'Bil. Lawatan',
        type: 'column',
        data: bilLawatanData
      }
    ],

    colors: [
      '#1CC700', // Seliaan (hijau)
      '#FDBA8C', // Membayar (oren)
      '#2563EB'  // Lawatan (biru solid)
    ],

    stroke: {
      width: [3, 3, 0],
      curve: 'smooth'
    },

    fill: {
      type: ['solid', 'solid', 'solid'], // ❌ TIADA gradient
      opacity: [1, 1, 1]
    },

    plotOptions: {
      bar: {
        columnWidth: '55%',
        borderRadius: 6,
        dataLabels: {
          position: 'top'
        }
      }
    },

    // dataLabels: {
    //   enabled: true,
    //   enabledOnSeries: [2], // ✅ HANYA LAWATAN ADA LABEL
    //   style: {
    //     fontSize: '12px',
    //     fontWeight: 'bold',
    //     colors: ['#ffffff']
    //   },
    //   formatter: (val) => val
    // },

    markers: {
      size: [4, 4, 0],
      strokeWidth: 2,
      hover: { sizeOffset: 2 }
    },

    xaxis: {
      categories,
      labels: {
        style: {
          fontSize: '12px',
          fontWeight: 500
        }
      }
    },

    yaxis: {
      labels: {
        formatter: (val) => Math.round(val)
      }
    },

    tooltip: {
      shared: true,
      intersect: false,
      custom: function ({ series, dataPointIndex, w }) {
        return `
          <div class="px-3 py-2 text-sm">
            <div><strong>Bulan:</strong> ${w.globals.categoryLabels[dataPointIndex]}</div>
            <div><strong>Bil. Seliaan:</strong> ${series[0][dataPointIndex]}</div>
            <div><strong>Bil. Membayar:</strong> ${series[1][dataPointIndex]}</div>
            <div><strong>Bil. Lawatan:</strong> ${series[2][dataPointIndex]}</div>
            <div><strong>% Lawat:</strong> ${percentLawatanData[dataPointIndex]}%</div>
          </div>
        `;
      }
    },

    legend: {
      position: 'bottom',
      fontSize: '13px'
    }
  };
};



document.addEventListener('DOMContentLoaded', function () {
	if (document.getElementById('bil-bayar-chart')) {
		const chartOptions = getBilMembayarChartOptions(window.chartData);
		const chart = new ApexCharts(document.getElementById('bil-bayar-chart'), chartOptions);
		chart.render();

		// init again when toggling dark mode
		document.addEventListener('dark-mode', function () {
			chart.updateOptions(getBilMembayarChartOptions(window.chartData));
		});
	}

	if (document.getElementById('pk-dk-chart')) {
		const chartOptions = getPkDkChartOptions(window.chartData);
		const chart = new ApexCharts(document.getElementById('pk-dk-chart'), chartOptions);
		chart.render();
	}

	if (document.getElementById('lawatan-chart')) {
		const chartOptions = getCombinedChartOptions(window.chartData);
		const chart = new ApexCharts(document.getElementById('lawatan-chart'), chartOptions);
		chart.render();

		// Re-initialize when toggling dark mode
		document.addEventListener('dark-mode', function () {
			chart.updateOptions(getCombinedChartOptions(window.chartData));
		});
	}
});