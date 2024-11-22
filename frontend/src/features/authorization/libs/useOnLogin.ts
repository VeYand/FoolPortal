import {userEntitySlice} from 'entities/user'
import {useEffect} from 'react'
import {useNavigate} from 'react-router-dom'
import {useLazyGetLoggedUser} from 'shared/libs/query'
import {remapApiUserToUserData} from 'shared/libs/remmapers'
import {useAppDispatch} from 'shared/redux'
import {UserPortalRoute} from 'shared/routes'

const useOnLogin = (isError: boolean, isLoading: boolean, isInitialized: boolean, isSuccess: boolean) => {
	const navigate = useNavigate()
	const [getLoggedUser] = useLazyGetLoggedUser()
	const dispatch = useAppDispatch()

	useEffect(() => {
		const callback = async () => {
			if (!isError && !isLoading && isInitialized && isSuccess) {
				const data = await getLoggedUser({})
				if (data.data) {
					const user = remapApiUserToUserData(data.data)
					dispatch(userEntitySlice.actions.setUser(user))
					navigate(UserPortalRoute.path)
				}
			}
		}

		callback()
	}, [dispatch, getLoggedUser, isError, isInitialized, isLoading, isSuccess, navigate])
}

export {
	useOnLogin,
}