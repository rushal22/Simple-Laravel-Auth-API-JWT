import React from "react";
import { AppBar, Grid, Toolbar , Button, Link } from "@mui/material";
import { useNavigate } from "react-router-dom";

const Navbar = ({ loggedIn, onLogout}) => {
  const navigate = useNavigate();

  const handleLogOut = () => {
    onLogout();
    navigate("/login");
  };

  const handleLogIn = () => {
    navigate("/login");
  };

  return (
    <AppBar position="fixed" sx={{ width: "100%", top: 0 }}>
      <Toolbar sx={{justifyContent: "space-between"}}>
        <Grid container justifyContent="space-between" alignItems="center">
          <Grid item>
          {loggedIn ? (
            <Button variant="contained" onClick={handleLogOut}>
              Logout
            </Button>
          ) : (
            <Button variant="contained" onClick={handleLogIn}>
              Login
            </Button>
          )}
          </Grid>
        
        <Grid item>
            <Link sx={{ color: "black" , textAlign: "center" }}>TrackOrder</Link>
          </Grid>
          <Grid item>
            <Link sx={{ color: "black"}}>Profile</Link>
          </Grid>
          </Grid>
      </Toolbar>
    </AppBar>
  );
};

export default Navbar;