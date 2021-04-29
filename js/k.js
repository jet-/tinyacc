document.addEventListener('DOMContentLoaded', () => {
    const mydate1 = new Cleave('.mydate1', {
        date: true,
	delimiter: '-',
	datePattern: ['Y', 'm', 'd']
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const mydate2 = new Cleave('.mydate2', {
        date: true,
	delimiter: '-',
	datePattern: ['Y', 'm', 'd']
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const cleave = new Cleave('.mynum', {
        numeral: true,
    	numaeralThousandGroupStyle: 'thousand'
    });
});
