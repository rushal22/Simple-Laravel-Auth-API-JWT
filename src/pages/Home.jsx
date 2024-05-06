import React from "react";
import Card from "@mui/material/Card";
import CardActions from "@mui/material/CardActions";
import CardContent from "@mui/material/CardContent";
import CardMedia from "@mui/material/CardMedia";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
// import image1 from '../image/image1.png'

const Home = () => {
  return (
    <main>
      {/* Hero unit */}
      <Container sx={{ py: 8 }} maxWidth="md">
        {/* End hero unit */}
        <Grid container spacing={4}>
          
            <Card
              sx={{ height: "100%", display: "flex", flexDirection: "column" }}
            >
              <CardMedia
                component="img"
                sx={{
                  width: "100%",
                  cursor: "pointer",
                }}
                image= {`https://plus.unsplash.com/premium_photo-1675896084254-dcb626387e1e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D`}
                alt="random"
              />
              {/* <img src={image1} />  */}
              <CardContent sx={{ flexGrow: 1 }}>
                <Typography
                  gutterBottom
                  variant="h5"
                  component="h2"
                ></Typography>
                <Typography></Typography>
              </CardContent>
              <CardActions>
                <Typography textAlign="left" variant="h6">
                  Rs.2000
                </Typography>
                <Typography textAlign="right" variant="h6">
                  {" "}
                  Product{" "}
                </Typography>
              </CardActions>
            </Card>
          </Grid>
      </Container>
    </main>
  );
};

export default Home;
