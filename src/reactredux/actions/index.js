import userData from "../../testData.json";
// import axios from "axios";
import { LOGIN_PENDING , LOGIN_ERROR } from "../reducers/user";
// import { LOGIN , LOGOUT } from "../reducers/userDetail";

export const logIn = (data) => {
  // console.log(data);
  return {
    type: "LOGIN",
    payload : data
  };
};

export const logOut = () => {
  return {
    type: "LOGOUT",
    payload: {}
  };
};

export const logInError = (error) => {
  return {
    type: LOGIN_ERROR,
    payload: error,
  };
};
export const logInPending = () => {
  return {
    type: LOGIN_PENDING,
  };
};
export const loginUser = (data) => {
  
  return (dispatch) => {
    try {
      // axios.get(`https://jsonplaceholder.typicode.com/todos/1`).then(res => {dispatch(logInSuccess(res))})
      dispatch(logInPending());
      
      dispatch(logIn(userData));

    } catch (error) {
      console.log(error);
      dispatch(logInError(error));
    }
  };
};

export const logOutUser = () => {
  return (dispatch) => {
    try{
      dispatch(logOut())
    }catch(err){
      dispatch(logInError(err));
    }
  }
}
