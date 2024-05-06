// export const LOGIN = "LOGIN";
// export const LOGOUT = "LOGOUT"
const initialState = {
        firstName : '',
        lastName : '',
        email : '',
        password : '',
        loggedIn : false
}   

const changeUserDetails = (state = initialState , action) => {
   switch (action.type) {
    case "LOGIN" :
        return {
        ...state , 
        firstName: action.payload.firstName,
        lastName: action.payload.lastName,
        email: action.payload.email,
        password: action.payload.password,
        loggedIn: true
    };
    case "LOGOUT":
    return initialState;
    default:
     return state
   }

}
export default changeUserDetails