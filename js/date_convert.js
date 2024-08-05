function convertDate(ValDate)
{
    if (ValDate == '') {
        return '';
    }else{

    let date = new Date(ValDate);
    let dd = String(date.getDate()).padStart(2, '0');
    let mm = String(date.getMonth() + 1).padStart(2,'0'); //January is 0!
    let yyyy = date.getFullYear();
    date = dd + '/' + mm + '/' + yyyy;
    return date;
    }
}
