import {Layout, Row, Col} from 'antd'
import {useEffect} from 'react'
import {useNavigate} from 'react-router-dom'
import {useAppSelector} from 'shared/redux'
import {UserPortalRoute} from 'shared/routes'
import {Authorization} from 'widgets/authorization/authorization'
import {Preloader} from 'widgets/preloader/Preloader'

const LoginPage = () => {
	const navigate = useNavigate()
	const {loading, initialized} = useAppSelector(state => state.userEntity)

	useEffect(() => {
		if (!loading && initialized) {
			navigate(UserPortalRoute.path)
		}
	}, [loading, initialized, navigate])

	if (loading) {
		return <Preloader/>
	}

	return (
		<Layout>
			<Row justify="center">
				<Col>
					<Authorization />
				</Col>
			</Row>
		</Layout>
	)
}

export {
	LoginPage,
}