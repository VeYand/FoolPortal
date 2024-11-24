import {Visibility, VisibilityOff} from '@mui/icons-material'
import {
	Box,
	TextField,
	Button,
	InputAdornment,
	IconButton,
	Typography,
} from '@mui/material'
import React, {useState} from 'react'
import {useNavigate} from 'react-router-dom'
import {getCsrfToken} from 'shared/libs'
import {useLazyLogin} from 'shared/libs/query'
import {UserPortalRoute} from 'shared/routes'

const Authorization = () => {
	const navigate = useNavigate()

	const [email, setEmail] = useState('')
	const [password, setPassword] = useState('')
	const [showPassword, setShowPassword] = useState(false)

	const [login] = useLazyLogin()

	const handleClickShowPassword = () => {
		setShowPassword(prev => !prev)
	}

	const handleSubmit = async (event: React.FormEvent) => {
		event.preventDefault()
		const data = await login({email, password, _csrf_token: getCsrfToken()})
		if (data.isSuccess) {
			navigate(UserPortalRoute.path)
		}
		else {
			console.log(data.error)
		}
	}

	return (
		<>
			<Typography variant="h4" gutterBottom>{'Войти'}</Typography>
			<Box
				component="form"
				onSubmit={handleSubmit}
				sx={{width: '300px', display: 'flex', flexDirection: 'column'}}
			>
				<TextField
					label="Email"
					variant="outlined"
					margin="normal"
					value={email}
					onChange={e => setEmail(e.target.value)}
					required
				/>
				<TextField
					label="Пароль"
					variant="outlined"
					margin="normal"
					type={showPassword ? 'text' : 'password'}
					value={password}
					onChange={e => setPassword(e.target.value)}
					required
					InputProps={{
						endAdornment: (
							<InputAdornment position="end">
								<IconButton onClick={handleClickShowPassword} edge="end">
									{showPassword ? <VisibilityOff/> : <Visibility/>}
								</IconButton>
							</InputAdornment>
						),
					}}
				/>
				<Button variant="contained" color="primary" type="submit">{'Войти'}</Button>
			</Box>
		</>
	)
}

export {
	Authorization,
}