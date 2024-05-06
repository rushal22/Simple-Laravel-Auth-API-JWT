
export const LOGIN_ERROR = "LOGIN_ERROR"
export const LOGIN_PENDING = "LOGIN_PENDING"

const initialState = {
    processing: false,
    error: {}
  };

const logInMessage = (state = initialState , action) => {
    switch (action.type) {
        case LOGIN_ERROR: 
        return {...state , processing: false, error: action.payload};
        case LOGIN_PENDING:
        return {...state , processing: true, error: {}}; 
        default:
        return state
    }
}

export default logInMessage;