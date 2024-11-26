import {Input, Button, Typography, Form, Alert} from 'antd'
import {useState} from 'react'
import {useNavigate} from 'react-router-dom'
import {getCsrfToken} from 'shared/libs'
import {useInitializeUser} from 'shared/libs/hooks'
import {useLazyLogin} from 'shared/libs/query'
import {UserPortalRoute} from 'shared/routes'
import styles from './Authorization.module.css'

const Authorization = () => {
	const navigate = useNavigate()

	const [email, setEmail] = useState('')
	const [password, setPassword] = useState('')
	const [error, setError] = useState<string | undefined>()
	const [login] = useLazyLogin()
	const {initialize} = useInitializeUser()

	const handleSubmit = async () => {
		setError(undefined)
		const data = await login({email, password, _csrf_token: getCsrfToken()})
		if (data.isSuccess) {
			initialize()
			navigate(UserPortalRoute.path)
		}
		else {
			setError('Ошибка входа. Пожалуйста, проверьте свои данные.')
		}
	}

	return (
		<div className={styles.container}>
			<Typography.Title level={2}>{'Student Portal'}</Typography.Title>
			<Form className={styles.form} onFinish={handleSubmit}>
				<Form.Item label="Email" name="email" rules={[{required: true, message: 'Введите ваш email'}]}>
					<Input value={email} onChange={e => setEmail(e.target.value)}/>
				</Form.Item>
				<Form.Item label="Пароль" name="password" rules={[{required: true, message: 'Введите ваш пароль'}]}>
					<Input.Password value={password} onChange={e => setPassword(e.target.value)} visibilityToggle/>
				</Form.Item>
				{error && <Alert message={error} type="error" showIcon className={styles.errorAlert}/>}
				<Form.Item>
					<Button type="primary" htmlType="submit">{'Войти'}</Button>
				</Form.Item>
			</Form>
		</div>
	)
}

export {
	Authorization,
}