import React from "react";
import axios from "axios";

class RegistrationForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      firstName: "",
      lastName: "",
      username: "",
      password: ""
    };
  }

  handleChange = (e) => {
    this.setState({
      [e.target.name]: e.target.value
    });
  };

  handleSubmit = async (e) => {
    e.preventDefault();

    try {
      // Send form data to API endpoint built in Java backend
      const response = await axios.post("API_ENDPOINT", {
        firstName: this.state.firstName,
        lastName: this.state.lastName,
        username: this.state.username,
        password: this.state.password
      });

      // Handle successful response
      console.log("Form data sent successfully:", response.data);

    } catch (error) {
      // Handle error
      console.error("Error sending form data:", error);
    }
  };

  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          <p>First Name:
          <input
            type="text"
            name="firstName"
            value={this.state.firstName}
            onChange={this.handleChange}
          />
          </p>
        </label>
        <label>
          <p>Last Name:
          <input
            type="text"
            name="lastName"
            value={this.state.lastName}
            onChange={this.handleChange}
          />
          </p>
        </label>
        <label>
          <p>Username:
          <input
            type="text"
            name="username"
            value={this.state.username}
            onChange={this.handleChange}
          />
          </p>
        </label>
        <label>
          <p>Password:
          <input
            type="password"
            name="password"
            value={this.state.password}
            onChange={this.handleChange}
          />
          </p>
        </label>
        <button type="button">Cancel</button>
        <button type="submit">Register</button>
      </form>
    );
  }
}

export default RegistrationForm;