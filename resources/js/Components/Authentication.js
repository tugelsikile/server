export const checkAuth = ()=> {
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('user');
    if (token === null || user === null) {
        logout();
    } else {
        return user;
    }
}
export const logout = ()=> {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = `${window.origin}/login`;
}
