
export const axiosHeader = () =>{
    const headers = {
        'Accept': 'application/json', 'Content-Type': 'application/json',
    };
    if (localStorage.getItem('token') !== null) headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
    return headers;
}
