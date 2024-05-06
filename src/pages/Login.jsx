import React from "react";
import Avatar from "@mui/material/Avatar";
import Button from "@mui/material/Button";
import CssBaseline from "@mui/material/CssBaseline";
import TextField from "@mui/material/TextField";
import Link from "@mui/material/Link";
import Grid from "@mui/material/Grid";
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import { useDispatch , useSelector  } from "react-redux";
import { loginUser } from "../reactredux/actions";
import { useState } from "react";
import userJsonData from "../testData.json";

import { useNavigate } from "react-router-dom"



const Login = () => {
  const navigate = useNavigate()  
  const dispatch = useDispatch()
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  // const { processing } = useSelector((state) => ({
  //   processing: state.logInMessage.processing
  // }));
    

const processing = useSelector((state) => state.logInMessage.processing);

// Memoize the selector
// const processedData = useMemo(() => ({
//   processing
// }), [processing]);
 

  const handleLogin = () => {
    let userData = {
      email : email,
      password : password
    }
    if(email === userJsonData.user.email && password === userJsonData.user.password){
      console.log(userData);
      dispatch(loginUser())
      navigate('/')
    }else{
      console.log("error occurred");
    }
  }
  
  return (
    <>

      <Container component="main" maxWidth="xs">
        <CssBaseline />
        <Box
          sx={{
            marginTop: 1,
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
          }}
        >
          <Avatar sx={{ m: 1, bgcolor: "blue" }}></Avatar>
          <Typography component="h1" variant="h5">
            Sign in
          </Typography>
          {processing && <p >Loading</p>}
          <Box  component="form" noValidate sx={{ mt: 1 }}>
            <TextField
              margin="normal"
              required
              fullWidth
              id="email"
              label="Email Address"
              name="email"
              autoComplete="email"
              autoFocus
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
            <TextField
              margin="normal"
              required
              fullWidth
              name="password"
              label="Password"
              type="password"
              id="password"
              autoComplete="current-password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
            <Button
              type="submit"
              onClick={handleLogin}
              variant="contained"
              sx={{ mt: 1, mb: 2 }}
            >
              Login
            </Button>
            {/* <button onClick={handleLogin}>LOGIN</button> */}
            <Grid container>
              <Grid item xs>
                <Link href="#" variant="body2">
                  Forgot password?
                </Link>
              </Grid>
              <Grid item>
                <Link href="/signup" variant="body2">
                  {"Don't have an account? Sign Up"}
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>
      </Container>
    </>
  );
};

export default Login;
