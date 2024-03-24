import React from "react";
import RegistrationForm from "./RegistrationForm";

class RegistrationPage extends React.Component{
    render() {
        return (
            <div>
                <h1>New User Registration</h1>
                <RegistrationForm/>
            </div>
            
        )
    }
}

export default RegistrationPage;