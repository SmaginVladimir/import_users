import React, {useEffect, useState} from 'react';
import {API_BASE_URL, AXIOS, INFO_USERS, USERS} from "../config";
import {Button, Container, Typography} from "@mui/material";
import {Ring} from "@uiball/loaders";
import '../App.css';

const Home = () => {

    const [isLoading, setIsLoading] = useState(false);
    const [message, setMessage] = useState('Импортировать юзеров');
    const [btnDisabled, setBtnDisabled] = useState(false);
    const [infoUsers, setInfoUsers] = useState({
        sum: 0,
        create: 0,
        update: 0
    });


    useEffect(() => {
        if (localStorage.getItem('keyUsers')) {
            setBtnDisabled(true);
            setMessage('Данные успешно отправленны, ожидайте ответ пожалуйста');
            requestUsersInfo(localStorage.getItem('keyUsers'));
        }
    }, []);


    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        try {
            const res = await AXIOS.get(API_BASE_URL + USERS);
            setIsLoading(false);
            setMessage('Данные успешно отправленны, ожидайте ответ пожалуйста...');
            setBtnDisabled(true);
            localStorage.setItem('keyUsers', res.data.userTask);
            requestUsersInfo(res.data.userTask);
        } catch (e) {
            setMessage('Что-то пошло не так , повторите попытку позже');
        }

    }

    const requestUsersInfo = (key) => {
        const interval = setInterval(() => {
            const res = AXIOS.get(API_BASE_URL + INFO_USERS + key);
            res.then(response => {
                const result = response.data.result;
                if (result) {
                    setInfoUsers(result);
                    clearInterval(interval);
                    localStorage.removeItem('keyUsers');
                    setMessage('Данные успешно сохраненны, следующие данные можно будет отправить через пару секунд');
                    setTimeout(() => {
                        setInfoUsers(prev => ({...prev, sum: 0, create: 0, update: 0}));
                        setBtnDisabled(false);
                        setMessage('Импортировать юзеров');
                    }, 15000);
                }
            });
        }, 10000);
    }

    return (
        <Container>
            <div className={'main'}>
                <Typography gutterBottom variant="h4" component="div">
                    Всего: <strong>{infoUsers.sum} </strong> Созданно: <strong>{infoUsers.create} </strong>
                    Обновленно: <strong>{infoUsers.update}</strong>
                </Typography>
                {isLoading ?
                    <>
                        <Typography gutterBottom variant="h5" component="div">
                            Идет загрузка подождите....
                        </Typography>
                        <Ring
                            size={70}
                            lineWeight={2}
                            speed={2}
                            color="black"
                        />
                    </>
                    :
                    <>
                        <Typography gutterBottom variant="h5" component="div">
                            {message}
                        </Typography>

                        <Button onClick={handleSubmit} type="submit"
                                variant="contained"
                                color="primary"
                                disabled={btnDisabled}
                        >Импротировать </Button>
                    </>
                }
            </div>

        </Container>
    );
};

export default Home;