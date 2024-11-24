import {useEffect} from 'react'
import {useNavigate} from 'react-router-dom'
import {useLazyGetLoggedUser} from 'shared/libs/query'
import {useAppDispatch} from 'shared/redux'

const useOnLogin = (isError: boolean, isLoading: boolean, isInitialized: boolean, isSuccess: boolean) => {
	const navigate = useNavigate()
	const [getLoggedUser] = useLazyGetLoggedUser()
	const dispatch = useAppDispatch()

	useEffect(() => {
		if (!isError && !isLoading && isInitialized && isSuccess) {
		}
	},
	[dispatch, getLoggedUser, isError, isInitialized, isLoading, isSuccess, navigate])
}

export {
	useOnLogin,
}