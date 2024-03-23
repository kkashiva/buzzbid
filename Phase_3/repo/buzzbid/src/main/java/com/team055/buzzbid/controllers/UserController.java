package com.team055.buzzbid.controllers;

import com.team055.buzzbid.models.User;
import com.team055.buzzbid.services.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("user")
public class UserController {

    @Autowired
    UserService userService;
    @GetMapping("{userName}")
    public User getUser(@PathVariable(value = "userName") String userName) throws Exception {
        User user = userService.getUser(userName);
        return user;
    }

    @PostMapping
    public void addUser(@RequestBody User user) throws Exception {
        userService.addUser(user);
    }
}
