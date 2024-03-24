import React from "react";


class RegistrationForm extends React.Component {
    render() {
        return (
            <form >
                <label>
                    <p>First Name:
                    <input type="text" />
                    </p>
                </label>
                <label>
                    <p>Last Name:
                    <input type="text" />
                    </p>
                </label>
                <label>
                    <p>Username:
                    <input type="text" />
                    </p>
                </label>
                <label>
                    <p>Password:
                    <input type="text" />
                    </p>
                </label>
                <button type="button">Cancel</button>
                <button type="submit">Register</button>
            </form>
        )
    }
}

export default RegistrationForm;