package com.team055.buzzbid.services;

import com.team055.buzzbid.models.User;
import com.team055.buzzbid.repositories.UserRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class UserService {
    private static final Logger log = LoggerFactory.getLogger(UserService.class);
    @Autowired
    UserRepository userRepository;

    public boolean addUser(User user) throws Exception {
        log.debug("Verifying user details for registration");
        if(user == null || user.getUserName() == null){
            throw new Exception("Invalid user details");
        }
        //todo: verify usernmae is unique
        return userRepository.addUser(user);

    }

    public User getUser(String userName) throws Exception {
        log.debug("Retrieving user details by user name");
        if(userName == null){
            throw new Exception("User name is required.");
        }
        return userRepository.getUser(userName);

    }
}
